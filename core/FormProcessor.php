<?php

/**
 * Class FormProcessor for processing forms
 */
class FormProcessor
{
	/**
	 * @var string
	 */
	private $request;
	/**
	 * @var string
	 */
	private $message;

	/**
	 * FormProcessor constructor.
	 * @param $request
	 * @param $message
	 */
	public function __construct($request, $message)
	{
		$this->request = $request;
		$this->message = $message;
	}

	/**
	 * @param $message_name
	 * @param $obj
	 * @param $fields
	 * @param array $checks
	 * @param bool $success_message
	 * @return null
	 */
	public function process($message_name, $obj, $fields, $checks = array(), $success_message = false)
	{
		try {
			if (is_null($this->checks($message_name, $checks))) return null; //проверка массива
			foreach ($fields as $field) {
				if (is_array($field)) {
					$f = $field[0];
					$v = $field[1];
					if (strpos($f, "()") !== false) {
						$f = str_replace("()", "", $f);
						$obj->$f($v);
					} else $obj->$f = $v;
				} else $obj->$field = $this->request->$field;
			}
			if ($obj->save()) {
				if ($success_message) $this->setSessionMessage($message_name, $success_message);
				return $obj;
			}
		} catch (Exception $e) {
			print_r($e);
			exit;
			$this->setSessionMessage($message_name, $this->getError($e));
			return null;
		}
	}

	/**
	 * @param $message_name
	 * @param $checks
	 * @return bool|null
	 */
	public function checks($message_name, $checks)
	{
		try {
			for ($i = 0; $i < count($checks); $i++) {
				$equal = isset($checks[$i][3]) ? $checks[$i][3] : true;
				if ($equal && ($checks[$i][0] != $checks[$i][1])) throw new Exception($checks[$i][2]);
				elseif (!$equal && ($checks[$i][0] == $checks[$i][1])) throw new Exception($checks[$i][2]);
			}
			return true;
		} catch (Exception $e) {
			$this->setSessionMessage($message_name, $this->getError($e));
			return null;
		}
	}

	/**
	 * @param $message_name
	 * @param $obj
	 * @param $method
	 * @param $login
	 * @param $password
	 * @return bool
	 */
	public function auth($message_name, $obj, $method, $login, $password)
	{ //метод авторизации
		try {
			$user = $obj::$method($login, $password);
			return $user;
		} catch (Exception $e) {
			$this->setSessionMessage($message_name, $this->getError($e));
			return false;
		}
	}

	/**
	 * @param $to
	 * @param $message
	 */
	public function setSessionMessage($to, $message)
	{
		if (!session_id()) session_start();
		$_SESSION["message"] = array($to => $message);
	}

	/**
	 * @param $to
	 * @return bool
	 */
	public function getSessionMessage($to)
	{
		if (!session_id()) session_start();
		if (!empty($_SESSION["message"]) && !empty($_SESSION["message"][$to])) { //проверям массив
			$message = $_SESSION["message"][$to]; //получаем переменную message
			unset($_SESSION["message"][$to]); //удаляем ее из сессии
			return $this->message->get($message);
		}
		return false;
	}

	/**
	 * @param $message_name
	 * @param $file
	 * @param $max_size
	 * @param $dir
	 * @param bool $source_name
	 * @return bool|mixed|string
	 */
	public function uploadIMG($message_name, $file, $max_size, $dir, $source_name = false)
	{ //метод загрузке изображений
		try {
			$name = File::uploadIMG($file, $max_size, $dir, false, $source_name);
			return $name;
		} catch (Exception $e) {
			$this->setSessionMessage($message_name, $this->getError($e));
			return false;
		}
	}

	/**
	 * @param $e
	 * @return mixed|string
	 */
	private function getError($e)
	{
		if ($e instanceof ValidatorException) {
			$error = current($e->getErrors());
			return $error[0];
		} elseif (($message = $e->getMessage())) return $message;
		return "UNKNOWN_ERROR";
	}

}

?>
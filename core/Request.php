<?php

/**
 * Class Request
 */
class Request
{
	/**
	 * @var array
	 */
	private static $sef_data = array();
	/**
	 * @var array|string
	 */
	private $data;

	/**
	 * Request constructor.
	 */
	public function __construct()
	{
		$this->data = $this->xss(array_merge($_REQUEST, self::$sef_data)); //пропускаем сразу же через xss уязвимости
	}

	/**
	 * @param $sef_data
	 */
	public static function addSEFData($sef_data)
	{
		self::$sef_data = $sef_data;
	}

	/**
	 * @param $name
	 * @return mixed|string
	 */
	public function __get($name)
	{ //метод доступа к элем. запроса
		if (isset($this->data[$name])) return $this->data[$name];
	}

	/**
	 * @param $data
	 * @return array|string
	 */
	private function xss($data)
	{
		if (is_array($data)) { //проверяем все данные
			$escaped = array(); //делаем их безопасными
			foreach ($data as $key => $value) {
				$escaped[$key] = $this->xss($value); //
			}
			return $escaped;
		}
		return trim(htmlspecialchars($data)); //убираем пробелы
	}

}

?>
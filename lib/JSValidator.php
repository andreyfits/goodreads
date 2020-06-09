<?php

/**
 * Class JSValidator
 */
class JSValidator
{
	/**
	 * @var string
	 */
	private $message; //определенные сообщения

	/**
	 * JSValidator constructor.
	 * @param $message
	 */
	public function __construct($message)
	{
		$this->message = $message;
	}

	/**
	 * Checking password
	 * @param bool $f_equal
	 * @param bool $min_len
	 * @param bool $t_empty
	 * @return stdClass
	 */
	public function password($f_equal = false, $min_len = true, $t_empty = false)
	{
		$cl = $this->getBase();
		if ($min_len) {
			$cl->min_len = ValidatePassword::MIN_LEN;
			$cl->t_min_len = $this->message->get(ValidatePassword::CODE_MIN_LEN);
		}
		$cl->max_len = ValidatePassword::MAX_LEN;
		$cl->t_max_len = $this->message->get(ValidatePassword::CODE_MAX_LEN);
		if ($t_empty) $cl->t_empty = $this->message->get($t_empty);
		else $cl->t_empty = $this->message->get(ValidatePassword::CODE_EMPTY);
		if ($f_equal) {
			$cl->f_equal = $f_equal;
			$cl->t_equal = $this->message->get("ERROR_PASSWORD_CONF");
		}
		return $cl;
	}

	/**
	 * Checking name
	 * @param bool $t_empty
	 * @param bool $t_max_len
	 * @param bool $t_type
	 * @return stdClass
	 */
	public function name($t_empty = false, $t_max_len = false, $t_type = false)
	{
		return $this->getBaseData($t_empty, $t_max_len, $t_type, "ValidateName", "name");
	}

	/**
	 * Checking login
	 * @param bool $t_empty
	 * @param bool $t_max_len
	 * @param bool $t_type
	 * @return stdClass
	 */
	public function login($t_empty = false, $t_max_len = false, $t_type = false)
	{
		return $this->getBaseData($t_empty, $t_max_len, $t_type, "ValidateLogin", "login");
	}

	/**
	 * Checking email
	 * @param bool $t_empty
	 * @param bool $t_max_len
	 * @param bool $t_type
	 * @return stdClass
	 */
	public function email($t_empty = false, $t_max_len = false, $t_type = false)
	{
		return $this->getBaseData($t_empty, $t_max_len, $t_type, "ValidateEmail", "email");
	}

	/**
	 * Checking avatar
	 * @return stdClass
	 */
	public function avatar()
	{
		$cl = $this->getBase();
		$cl->t_empty = $this->message->get("ERROR_AVATAR_EMPTY");
		return $cl;
	}

	/**
	 * Checking captcha
	 * @return stdClass
	 */
	public function captcha()
	{
		$cl = $this->getBase();
		$cl->t_empty = $this->message->get("ERROR_CAPTCHA_EMPTY");
		return $cl;
	}

	/**
	 * @param $t_empty
	 * @param $t_max_len
	 * @param $t_type
	 * @param $class
	 * @param $type
	 * @return stdClass
	 */
	private function getBaseData($t_empty, $t_max_len, $t_type, $class, $type)
	{
		$cl = $this->getBase();
		$cl->type = $type;
		$cl->max_len = $class::MAX_LEN;

		if ($t_empty) $cl->t_empty = $this->message->get($t_empty);
		else $cl->t_empty = $this->message->get($class::CODE_EMPTY);
		if ($t_max_len) $cl->t_max_len = $this->message->get($t_max_len);
		else $cl->t_max_len = $this->message->get($class::CODE_MAX_LEN);
		if ($t_type) $cl->t_type = $this->message->get($t_type);
		else $cl->t_type = $this->message->get($class::CODE_INVALID);
		return $cl;
	}

	/**
	 * Validation of user input
	 * @return stdClass
	 */
	private function getBase()
	{
		$cl = new stdClass(); //стандартный класс встроенный в php
		$cl->type = ""; //тип данных
		$cl->min_len = ""; //мин. длина
		$cl->max_len = ""; //макс. длина
		$cl->t_min_len = ""; //текст, кот. будет выдодится пол-лю, если он ввел слишком короткое знач. в поле
		$cl->t_max_len = ""; //текст, кот. будет выдодится пол-лю, если он ввел слишком большое знач. в поле
		$cl->t_empty = ""; //если пол-ль не заполнил данное поле
		$cl->t_type = ""; //текст, при некорректном заполнении
		$cl->f_equal = ""; //текст для проверки подтверж. паролей
		$cl->t_equal = "";
		return $cl;
	}

}

?>
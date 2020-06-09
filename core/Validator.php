<?php

/**
 * Class Validator
 */
abstract class Validator
{
	const CODE_UNKNOWN = "UNKNOWN_ERROR";

	/**
	 * @var array
	 */
	protected $data; //массив проверки данных
	/**
	 * @var array
	 */
	private $errors = array(); //массив ошибок

	/**
	 * Validator constructor.
	 * @param $data
	 */
	public function __construct($data)
	{
		$this->data = $data;
		$this->validate();
	}

	/**
	 * @return mixed
	 */
	abstract protected function validate();

	/**
	 * Getting errors
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * @return bool
	 */
	public function isValid()
	{
		return count($this->errors) == 0;
	}

	/**
	 * Setting errors
	 * @param $code
	 */
	protected function setError($code)
	{
		$this->errors[] = $code;
	}

	/**
	 * Checking login input(different quotes)
	 * @param $string
	 * @return bool
	 */
	protected function isContainQuotes($string)
	{
		$array = array("\"", "'", "`", "&quot;", "&apos;");
		foreach ($array as $value) {
			if (strpos($string, $value) !== false) return true;
		}
		return false;
	}

}

?>
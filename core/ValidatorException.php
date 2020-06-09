<?php

/**
 * Class ValidatorException to store all errors
 */
class ValidatorException extends Exception
{
	/**
	 * @var
	 */
	private $errors;

	/**
	 * ValidatorException constructor.
	 * @param $errors
	 */
	public function __construct($errors)
	{
		parent::__construct(); //вызываем род. конструктор класса Exception
		$this->errors = $errors;
	}

	/**
	 * Getting all errors
	 * @return mixed
	 */
	public function getErrors()
	{
		return $this->errors;
	}

}

?>
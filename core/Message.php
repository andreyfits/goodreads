<?php

/**
 * Class Message responsible for the system messages
 */
class Message
{
	/**
	 * @var array|false
	 */
	private $data;

	/**
	 * Message constructor.
	 * @param $file
	 */
	public function __construct($file)
	{
		$this->data = parse_ini_file($file);
	}

	/**
	 * @param $name
	 * @return mixed
	 */
	public function get($name)
	{
		return $this->data[$name];
	}

}

?>
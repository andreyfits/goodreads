<?php

/**
 * Class ObjectDB
 */
abstract class ObjectDB extends AbstractObjectDB
{
	/**
	 * @var string[]
	 */
	private static $months = array("янв", "фев", "март", "апр", "май", "июнь", "июль", "авг", "сен", "окт", "ноя", "дек");

	/**
	 * ObjectDB constructor.
	 * @param $table
	 */
	public function __construct($table)
    {
        parent::__construct($table, Config::FORMAT_DATE);
    }

	/**
	 * Getting month
	 * @param bool $date
	 * @return string
	 */
	protected static function getMonth($date = false)
    {
        if ($date) $date = strtotime($date);
        else $date = time();
        return self::$months[date("n", $date) - 1];
    }

	/**
	 * @param $field
	 * @param $value
	 * @return bool
	 */
	public function preEdit($field, $value)
    {
        return true;
    }

	/**
	 * @param $field
	 * @param $value
	 * @return bool
	 */
	public function postEdit($field, $value)
    {
        return true;
    }

	/**
	 * @param $auth_user
	 * @param $field
	 * @return bool
	 */
	public function accessEdit($auth_user, $field)
    {
        return false;
    }

	/**
	 * @param $auth_user
	 * @return bool
	 */
	public function accessDelete($auth_user)
    {
        return false;
    }

}

?>
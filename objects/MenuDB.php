<?php

/**
 * Class MenuDB
 */
class MenuDB extends ObjectDB
{
	/**
	 * @var string
	 */
	protected static $table = "menu";

	/**
	 * MenuDB constructor.
	 */
	public function __construct()
    {
        parent::__construct(self::$table);
        $this->add("type", "ValidateID");
        $this->add("title", "ValidateTitle");
        $this->add("link", "ValidateURL");
        $this->add("parent_id", "ValidateID");
        $this->add("external", "ValidateBoolean");
    }

	/**
	 * Getting top menu
	 * @return array
	 */
	public static function getTopMenu()
    {
        return ObjectDB::getAllOnField(self::$table, __CLASS__, "type", TOPMENU, "id");
    }

	/**
	 * Getting main menu
	 * @return array
	 */
	public static function getMainMenu()
    {
        return ObjectDB::getAllOnField(self::$table, __CLASS__, "type", MAINMENU, "id");
    }

}

?>
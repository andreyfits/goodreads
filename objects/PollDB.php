<?php

/**
 * Class PollDB
 */
class PollDB extends ObjectDB
{
	/**
	 * @var string
	 */
	protected static $table = "polls";

	/**
	 * PollDB constructor.
	 */
	public function __construct()
    {
        parent::__construct(self::$table);
        $this->add("title", "ValidateTitle");
        $this->add("state", "ValidateBoolean", null, 0);
    }

	/**
	 * Random poll
	 * @return bool
	 */
	public function loadRandom()
    {
        $select = new Select(self::$db);
        $select->from(self::$table, "*")
            ->where("`state` = " . self::$db->getSQ(), array(1))
            ->rand()//сорт. случайным образом
            ->limit(1); //1 опрос
        $row = self::$db->selectRow($select); //получаем строку
        return $this->init($row);
    }

}

?>
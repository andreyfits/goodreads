<?php

/**
 * Class QuoteDB
 */
class QuoteDB extends ObjectDB
{
	/**
	 * @var string
	 */
	protected static $table = "quotes";

	/**
	 * QuoteDB constructor.
	 */
	public function __construct()
    {
        parent::__construct(self::$table);
        $this->add("author", "ValidateTitle");
        $this->add("text", "ValidateSmallText");
    }

	/**
	 * Random quote
	 * @return bool
	 */
	public function loadRandom()
    {
        $select = new Select(self::$db);
        $select->from(self::$table, "*")
            ->rand()
            ->limit(1);
        $row = self::$db->selectRow($select);
        return $this->init($row);
    }

}

?>
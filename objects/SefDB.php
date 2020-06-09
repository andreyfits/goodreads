<?php

/**
 * Class SefDB
 */
class SefDB extends ObjectDB
{
	/**
	 * @var string
	 */
	protected static $table = "sef";

	/**
	 * SefDB constructor.
	 */
	public function __construct()
    {
        parent::__construct(self::$table);
        $this->add("link", "ValidateURI");
        $this->add("alias", "ValidateTitle");
    }

	/**
	 * @param $link
	 * @return bool
	 */
	public function loadOnLink($link)
    {
        return $this->loadOnField("link", $link);
    }

	/**
	 * @param $alias
	 * @return bool
	 */
	public function loadOnAlias($alias)
    {
        return $this->loadOnField("alias", $alias);
    }

	/**
	 * @param $link
	 * @return mixed
	 */
	public static function getAliasOnLink($link)
    {
        $select = new Select(self::$db);
        $select->from(self::$table, array("alias"))
            ->where("`link` = " . self::$db->getSQ(), array($link));
        return self::$db->selectCell($select);
    }

	/**
	 * @param $alias
	 * @return mixed
	 */
	public static function getLinkOnAlias($alias)
    {
        $select = new Select(self::$db);
        $select->from(self::$table, array("link"))
            ->where("`alias` = " . self::$db->getSQ(), array($alias));
        return self::$db->selectCell($select);
    }

}

?>
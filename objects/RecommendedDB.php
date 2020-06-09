<?php

/**
 * Class RecommendedDB
 */
class RecommendedDB extends ObjectDB
{ //класс банера
	/**
	 * @var string
	 */
	protected static $table = "recommended";

	/**
	 * RecommendedDB constructor.
	 */
	public function __construct()
    {
        parent::__construct(self::$table);
        $this->add("type", "ValidateRecommendedType");
        $this->add("header", "ValidateTitle");
        $this->add("sub_header", "ValidateTitle");
        $this->add("img", "ValidateIMG");
        $this->add("link", "ValidateURL");
        $this->add("text", "ValidateText");
        $this->add("did", "ValidateID");
        $this->add("latest", "ValidateBoolean");
        $this->add("section_ids", "ValidateIDs");
    }

	/**
	 * @return bool
	 */
	protected function postInit()
    {
        $this->img = Config::DIR_IMG . $this->img;
        return true;
    }

	/**
	 * The section id will load a specific course into the RecommendedDB object
	 * @param $section_id
	 * @param $type
	 * @throws Exception
	 */
	public function loadOnSectionID($section_id, $type)
    {
        $select = new Select();
        $select->from(self::$table, "*")
            ->where("`type` = " . self::$db->getSQ(), array($type))
            ->where("`latest` = " . self::$db->getSQ(), array(1))
            ->rand();
        $data_1 = self::$db->select($select); //получаем 1 набор данных
        $select = new Select();
        $select->from(self::$table, "*")
            ->where("`type` = " . self::$db->getSQ(), array($type));
        if ($section_id) $select->whereFIS("section_ids", $section_id); //доб. проверку на раздел
        $select->rand();
        $data_2 = self::$db->select($select); //получаем 2 набор данных
        $data = array_merge($data_1, $data_2); //объединяем массивы
        if (count($data) == 0) {
            $select = new Select();
            $select->from(self::$table, "*")
                ->where("`type` = " . self::$db->getSQ(), array($type))
                ->rand();
            $data = self::$db->select($select); //получаем двум. массив данных
        }
        $data = ObjectDB::buildMultiple(__CLASS__, $data); //преобразуем в набор объектов
        uasort($data, array(__CLASS__, "compare"));
        $first = array_shift($data); //берем первый эл. у сортировки
        $this->load($first->id);
    }

	/**
	 * @param $value_1
	 * @param $value_2
	 * @return bool|int
	 */
	private function compare($value_1, $value_2)
    {
        if ($value_1->latest != $value_2->latest) return $value_1->latest < $value_2->latest;
        if ($value_1->type == $value_2->type) return 0;
        return $value_1->type > $value->type;
    }

}

?>
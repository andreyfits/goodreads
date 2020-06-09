<?php

/**
 * Class AbstractSelect
 */
class AbstractSelect
{
	/**
	 * @var $db
	 */
	private $db;
	/**
	 * @var string
	 */
	private $from = ""; //из какой таблицы происходит выборка
	/**
	 * @var string
	 */
	private $where = "";
	/**
	 * @var string
	 */
	private $order = ""; //сортировка
	/**
	 * @var string
	 */
	private $limit = "";

	/**
	 * AbstractSelect constructor.
	 * @param $db
	 */
	public function __construct($db)
	{
		$this->db = $db;
	}

	/**
	 * Writing a value from "FROM"
	 * @param $table_name
	 * @param $fields
	 * @return $this
	 */
	public function from($table_name, $fields)
	{
		$table_name = $this->db->getTableName($table_name); //получаем полное название таблицы
		$from = "";
		if ($fields == "*") $from = "*";
		else {
			for ($i = 0; $i < count($fields); $i++) {
				if (($pos_1 = strpos($fields[$i], "(")) !== false) { //явл. ли извлекаемое поле функцией
					$pos_2 = strpos($fields[$i], ")");
					$from .= substr($fields[$i], 0, $pos_1) . "(`" . substr($fields[$i], $pos_1 + 1, $pos_2 - $pos_1 - 1) . "`),"; //преобразовываем строку
				} else $from .= "`" . $fields[$i] . "`,";
			}
			$from = substr($from, 0, -1); //удаляем запятую
		}
		$from .= " FROM `$table_name`";
		$this->from = $from;
		return $this;
	}

	/**
	 * Adding predicates to a query
	 * @param $where
	 * @param array $values
	 * @param bool $and
	 * @return $this
	 */
	public function where($where, $values = array(), $and = true)
	{
		if ($where) { //если запрос передан
			$where = $this->db->getQuery($where, $values); //получаем запрос с безопасными данными
			$this->addWhere($where, $and); //доб. запрос в наше поле
		}
		return $this;
	}

	/**
	 * Extracting data(`id` IN (1,6,3))
	 * @param $field
	 * @param $values
	 * @param bool $and
	 * @return $this
	 */
	public function whereIn($field, $values, $and = true)
	{
		$where = "`$field` IN ("; //форм. поле
		foreach ($values as $value) {
			$where .= $this->db->getSQ() . ",";
		}
		$where = substr($where, 0, -1); //удаляем запятую
		$where .= ")"; //закрываем скобку множества
		return $this->where($where, $values, $and);
	}

	/**
	 * @param $col_name
	 * @param $value
	 * @param bool $and
	 * @return $this
	 */
	public function whereFIS($col_name, $value, $and = true)
	{
		$where = "FIND_IN_SET (" . $this->db->getSQ() . ", `$col_name`) > 0";
		return $this->where($where, array($value), $and);
	}

	/**
	 * Order
	 * @param $field
	 * @param bool $ask
	 * @return $this
	 */
	public function order($field, $ask = true)
	{
		if (is_array($field)) {
			$this->order = "ORDER BY ";
			if (!is_array($ask)) { //если не массив
				$temp = array(); //заполняем массив
				for ($i = 0; $i < count($field); $i++) $temp[] = $ask; //
				$ask = $temp;
			}
			for ($i = 0; $i < count($field); $i++) { //перебираем поля
				$this->order .= "`" . $field[$i] . "`";
				if (!$ask[$i]) $this->order .= " DESC,"; //если сортировка по убыванию
				else $this->order .= ","; //если сортировка по возврастани.
			}
			$this->order = substr($this->order, 0, -1); //убираем запятую
		} else {
			$this->order = "ORDER BY `$field`"; //если одно поле: то по возвр.
			if (!$ask) $this->order .= " DESC"; //если одно поле: то по убыв.
		}
		return $this;
	}

	/**
	 * The number of records to retrieve, and the offset parameter
	 * @param $count
	 * @param int $offset
	 * @return $this|bool
	 */
	public function limit($count, $offset = 0)
	{
		$count = (int)$count; //преобразование в число
		$offset = (int)$offset; //преобразование в число
		if ($count < 0 || $offset < 0) return false; //если отриц. число записей и смещения
		$this->limit = "LIMIT $offset, $count";
		return $this;
	}

	/**
	 * Extracting random records
	 * @return $this
	 */
	public function rand()
	{
		$this->order = "ORDER BY RAND()";
		return $this;
	}

	/**
	 * Converting an object to a string with the SELECT query
	 * @return string
	 */
	public function __toString()
	{
		if ($this->from) $ret = "SELECT " . $this->from . " " . $this->where . " " . $this->order . " " . $this->limit;
		else $ret = "";
		return $ret;
	}

	/**
	 * @param $where
	 * @param $and
	 */
	private function addWhere($where, $and)
	{
		if ($this->where) { //если уже предикат какой есть
			if ($and) $this->where .= " AND ";
			else $this->where .= " OR ";
			$this->where .= $where; //доб. сам where
		} else $this->where = "WHERE $where"; //форм. сам where
	}
}

?>
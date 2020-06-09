<?php

/**
 * Abstract class AbstractDataBase for working with database objects
 */
abstract class AbstractDataBase
{
	/**
	 * @var mysqli
	 */
	private $mysqli;
	/**
	 * @var $sq
	 */
	private $sq;
	/**
	 * @var $prefix
	 */
	private $prefix;

	/**
	 * AbstractDataBase constructor.
	 * @param $db_host
	 * @param $db_user
	 * @param $db_password
	 * @param $db_name
	 * @param $sq
	 * @param $prefix
	 */
	protected function __construct($db_host, $db_user, $db_password, $db_name, $sq, $prefix)
	{
		$this->mysqli = @new mysqli($db_host, $db_user, $db_password, $db_name);
		if ($this->mysqli->connect_errno) exit("Ошибка соединения с базой данных");
		$this->sq = $sq; //присваивам значения переданные в параметры конструктора
		$this->prefix = $prefix;
		$this->mysqli->query("SET lc_time_names = 'ru_RU'");
		$this->mysqli->set_charset("utf8");
	}

	/**
	 * @return mixed
	 */
	public function getSQ()
	{
		return $this->sq;
	}

	/**
	 * Getting query
	 * @param $query
	 * @param $params
	 * @return string|string[]
	 */
	public function getQuery($query, $params)
	{
		if ($params) {
			$offset = 0;
			$len_sq = strlen($this->sq);
			for ($i = 0; $i < count($params); $i++) { //находим все знаки вопроса
				$pos = strpos($query, $this->sq, $offset); //начало позиции знака ?
				if (is_null($params[$i])) $arg = "NULL";
				else $arg = "'" . $this->mysqli->real_escape_string($params[$i]) . "'";
				$query = substr_replace($query, $arg, $pos, $len_sq); //замена подстроки
				$offset = $pos + strlen($arg); //делаем смещение, чтобы искать от новой позиции
			}
		}
		return $query;
	}

	/**
	 * Select query
	 * @param AbstractSelect $select
	 * @return array|bool
	 */
	public function select(AbstractSelect $select)
	{
		$result_set = $this->getResultSet($select, true, true);
		if (!$result_set) return false;
		$array = array();
		while (($row = $result_set->fetch_assoc()) != false)
			$array[] = $row;
		return $array;
	}

	/**
	 * Getting rows from a select query
	 * @param AbstractSelect $select
	 * @return array|bool|null
	 */
	public function selectRow(AbstractSelect $select)
	{ //метод получает получить строку
		$result_set = $this->getResultSet($select, false, true);
		if (!$result_set) return false;
		return $result_set->fetch_assoc();
	}

	/**
	 * Getting columns from a select query
	 * @param AbstractSelect $select
	 * @return array|bool
	 */
	public function selectCol(AbstractSelect $select)
	{
		$result_set = $this->getResultSet($select, true, true);
		if (!$result_set) return false;
		$array = array();
		while (($row = $result_set->fetch_assoc()) != false) {
			foreach ($row as $value) {
				$array[] = $value;
				break;
			}
		}
		return $array;
	}

	/**
	 * Extract the contents of the cell
	 * @param AbstractSelect $select
	 * @return bool|mixed
	 */
	public function selectCell(AbstractSelect $select)
	{
		$result_set = $this->getResultSet($select, false, true);
		if (!$result_set) return false;
		$arr = array_values($result_set->fetch_assoc()); //выберем значения
		return $arr[0];
	}

	/**
	 * Inserting data into a table
	 * @param $table_name
	 * @param $row
	 * @return bool|mixed
	 */
	public function insert($table_name, $row)
	{
		if (count($row) == 0) return false;
		$table_name = $this->getTableName($table_name);
		$fields = "(";
		$values = "VALUES (";
		$params = array(); //массив параметров
		foreach ($row as $key => $value) {
			$fields .= "`$key`,";
			$values .= $this->sq . ",";
			$params[] = $value;
		}
		$fields = substr($fields, 0, -1); //удаляем последний символ
		$values = substr($values, 0, -1);
		$fields .= ")";
		$values .= ")";
		$query = "INSERT INTO `$table_name` $fields $values";
		return $this->query($query, $params);
	}

	/**
	 * Updating data in the table
	 * @param $table_name
	 * @param $row
	 * @param bool $where
	 * @param array $params
	 * @return bool|mixed
	 */
	public function update($table_name, $row, $where = false, $params = array())
	{
		if (count($row) == 0) return false;
		$table_name = $this->getTableName($table_name);
		$query = "UPDATE `$table_name` SET ";
		$params_add = array();
		foreach ($row as $key => $value) {
			$query .= "`$key` = " . $this->sq . ",";
			$params_add[] = $value;
		}
		$query = substr($query, 0, -1);
		if ($where) {
			$params = array_merge($params_add, $params); //объединяем массивы
			$query .= " WHERE $where";
		}
		return $this->query($query, $params);
	}

	/**
	 * Deleting data from a table
	 * @param $table_name
	 * @param bool $where
	 * @param array $params
	 * @return bool|mixed
	 */
	public function delete($table_name, $where = false, $params = array())
	{
		$table_name = $this->getTableName($table_name);
		$query = "DELETE FROM `$table_name`";
		if ($where) $query .= " WHERE $where";
		return $this->query($query, $params);
	}

	/**
	 * Getting the full table name
	 * @param $table_name
	 * @return string
	 */
	public function getTableName($table_name)
	{
		return $this->prefix . $table_name;
	}

	/**
	 * Getting query
	 * @param $query
	 * @param bool $params
	 * @return bool|mixed
	 */
	private function query($query, $params = false)
	{
		$success = $this->mysqli->query($this->getQuery($query, $params));
		if (!$success) return false;
		if ($this->mysqli->insert_id === 0) return true;
		return $this->mysqli->insert_id; //id последней вставленной записи
	}

	/**
	 * Access from the outside
	 * @param AbstractSelect $select
	 * @param $zero
	 * @param $one
	 * @return bool|mysqli_result
	 */
	private function getResultSet(AbstractSelect $select, $zero, $one)
	{
		$result_set = $this->mysqli->query($select);
		if (!$result_set) return false;
		if ((!$zero) && ($result_set->num_rows == 0)) return false;
		if ((!$one) && ($result_set->num_rows == 1)) return false;
		return $result_set;
	}

	/**
	 * Closing a DB connection
	 */
	public function __destruct()
	{
		if (($this->mysqli) && (!$this->mysqli->connect_errno)) $this->mysqli->close();
	}

}

?>
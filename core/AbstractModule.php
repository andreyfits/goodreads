<?php

/**
 * Class AbstractModule responsible for the operation of modules
 */
abstract class AbstractModule
{
	/**
	 * @var array
	 */
	private $properties = array();
	/**
	 * @var
	 */
	private $view; //шаблонизатор

	/**
	 * AbstractModule constructor.
	 * @param $view
	 */
	public function __construct($view)
	{
		$this->view = $view;
	}

	/**
	 * Adding a new property for the module
	 * @param $name
	 * @param null $default
	 * @param bool $is_array
	 */
	final protected function add($name, $default = null, $is_array = false)
	{ //
		$this->properties[$name]["is_array"] = $is_array;
		if ($is_array && $default == null) $this->properties[$name]["value"] = array();
		else $this->properties[$name]["value"] = $default;
	}

	/**
	 * Magic method getting the set value
	 * @param $name
	 * @return mixed|null
	 */
	final public function __get($name)
	{
		if (array_key_exists($name, $this->properties)) return $this->properties[$name]["value"];
		return null;
	}

	/**
	 * Magic method for setting the value
	 * @param $name
	 * @param $value
	 * @return bool
	 */
	final public function __set($name, $value)
	{
		if (array_key_exists($name, $this->properties)) {
			if (is_array($this->properties[$name]["value"])) {
				if (is_array($value)) $this->properties[$name]["value"] = $value;
				else $this->properties[$name]["value"][] = $value;
			} else $this->properties[$name]["value"] = $value;
		} else return false;
	}

	/**
	 * Return keys and values for properties
	 * @return array
	 */
	final protected function getProperties()
	{
		$ret = array();
		foreach ($this->properties as $name => $value) {
			$ret[$name] = $value["value"];
		}
		return $ret;
	}

	/**
	 * @param $name
	 * @param $field
	 * @param $obj
	 */
	final protected function addObj($name, $field, $obj)
	{
		if (array_key_exists($name, $this->properties)) $this->properties[$name]["value"][$field] = $obj;
	}

	/**
	 * Working with complex data
	 * @param $obj
	 * @param $field
	 * @return mixed
	 */
	final protected function getComplexValue($obj, $field)
	{
		if (strpos($field, "->") !== false) $field = explode("->", $field);
		if (is_array($field)) {
			$value = $obj;
			foreach ($field as $f) $value = $value->{$f};
		} else $value = $obj->$field;
		return $value;
	}

	/**
	 * Converting an object to a string
	 * @return mixed
	 */
	final public function __toString()
	{
		$this->preRender();
		return $this->view->render($this->getTmplFile(), $this->getProperties(), true); //обращаемся к шаблонизатору, возв. ему наши тпл файл, передаем ему все наши нас-ки
	}

	/**
	 *
	 */
	protected function preRender()
	{
	}

	/**
	 * The declension of words
	 * @param $number
	 * @param $suffix
	 * @return mixed
	 */
	final protected function numberOf($number, $suffix)
	{
		$keys = array(2, 0, 1, 1, 1, 2);
		$mod = $number % 100;
		$suffix_key = ($mod > 7 && $mod < 20) ? 2 : $keys[min($mod % 10, 5)];
		return $suffix[$suffix_key];
	}

	/**
	 * Getting the TPL file
	 * @return mixed
	 */
	abstract public function getTmplFile();

}

?>
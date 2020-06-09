<?php

/**
 * Class SectionDB
 */
class SectionDB extends ObjectDB
{
	/**
	 * @var string
	 */
	protected static $table = "sections";

	/**
	 * SectionDB constructor.
	 */
	public function __construct()
	{
		parent::__construct(self::$table);
		$this->add("title", "ValidateTitle");
		$this->add("img", "ValidateIMG");
		$this->add("description", "ValidateText");
		$this->add("meta_desc", "ValidateMD");
		$this->add("meta_key", "ValidateMK");
	}

	/**
	 * @return bool
	 */
	protected function postInit()
	{
		if (!is_null($this->img)) $this->img = Config::DIR_IMG_ARTICLES . $this->img;
		$this->link = URL::get("section", "", array("id" => $this->id));
		return true;
	}

	/**
	 * @return bool
	 */
	protected function preValidate()
	{
		if (!is_null($this->img)) $this->img = basename($this->img);
		return true;
	}

	/**
	 * @param $auth_user
	 * @param $field
	 * @return bool
	 */
	public function accessEdit($auth_user, $field)
	{
		if ($field == "title") return true;
		return false;
	}

	/**
	 * @param $auth_user
	 * @return bool
	 */
	public function accessDelete($auth_user)
	{
		return true;
	}

}

?>
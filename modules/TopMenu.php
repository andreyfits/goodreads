<?php

class TopMenu extends Module
{ //класс отвечающий за верхнее меню
	public function __construct()
	{
		parent::__construct();
		$this->add("uri");
		$this->add("items", null, true);
	}

	public function getTmplFile()
	{
		return "topmenu";
	}

}

?>
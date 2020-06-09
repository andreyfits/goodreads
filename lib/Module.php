<?php

/**
 * Class Module
 */
abstract class Module extends AbstractModule
{
	/**
	 * Module constructor.
	 */
	public function __construct()
	{
		parent::__construct(new View(Config::DIR_TMPL));
	}

}

?>
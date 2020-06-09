<?php

/**
 * Class Select
 */
class Select extends AbstractSelect
{
	/**
	 * Select constructor.
	 */
	public function __construct()
    {
        parent::__construct(DataBase::getDBO());
    }

}

?>
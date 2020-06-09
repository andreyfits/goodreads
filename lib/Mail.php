<?php

/**
 * Class Mail
 */
class Mail extends AbstractMail
{
	/**
	 * Mail constructor.
	 */
	public function __construct()
    {
        parent::__construct(new View(Config::DIR_EMAILS), Config::ADM_EMAIL);
        $this->setFromName(Config::ADM_NAME); //имя отправителя(для защиты от спам-фильтров)
    }

}

?>
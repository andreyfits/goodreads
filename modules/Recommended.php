<?php

class Recommended extends Module
{ //класс рекомендаций
    public function __construct()
    {
        parent::__construct();
        $this->add("auth_user");
        $this->add("recommendeds", null, true);
    }

    public function getTmplFile()
    {
        return "recommended";
    }

}

?>
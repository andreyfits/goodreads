<?php

class Intro extends ModuleHornav
{ //метод введения
    public function __construct()
    {
        parent::__construct();
        $this->add("obj"); //доб. св-во
    }

    public function getTmplFile()
    {
        return "intro";
    }

}

?>
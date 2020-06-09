<?php

class Slider extends Module
{ //верхний слайдер
    public function __construct()
    {
        parent::__construct();
        $this->add("recommended");
    }

    public function getTmplFile()
    {
        return "slider";
    }

}

?>
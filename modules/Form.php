<?php

class Form extends ModuleHornav
{ //класс для форм
    public function __construct()
    {
        parent::__construct();
        $this->add("name");
        $this->add("action");
        $this->add("method", "post");
        $this->add("header");
        $this->add("message");
        $this->add("check", true);
        $this->add("enctype");
        $this->add("inputs", null, true);
        $this->add("jsv", null, true);
    }

    public function addJSV($field, $jsv)
    { //метод проверки валидации
        $this->addObj("jsv", $field, $jsv);
    }

    public function text($name, $label = "", $value = "", $default_v = "")
    { //метод отвечающий за текстовое поле
        $this->input($name, "text", $label, $value, $default_v);
    }

    public function password($name, $label = "", $default_v = "")
    { //метод отвечающий за пароль
        $this->input($name, "password", $label, "", $default_v);
    }

    public function captcha($name, $label)
    { //метод отвечающий за капчу
        $this->input($name, "captcha", $label);
    }

    public function file($name, $label)
    { //метод отвечающий за файлы
        $this->input($name, "file", $label);
    }

    public function hidden($name, $value)
    { //метод отвечающий за скрытые эл.
        $this->input($name, "hidden", "", $value);
    }

    public function submit($value, $name = false)
    { //метод отвечающий за отравку формы
        $this->input($name, "submit", "", $value);
    }

    private function input($name, $type, $label, $value = false, $default_v = false)
    { //метод отвечающий за инпуты
        $cl = new stdClass();
        $cl->name = $name;
        $cl->type = $type;
        $cl->label = $label;
        $cl->value = $value;
        $cl->default_v = $default_v;
        $this->inputs = $cl;
    }

    public function getTmplFile()
    {
        return "form";
    }

}

?>
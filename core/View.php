<?php

/**
 * Class View
 */
class View
{

	/**
	 * @var string
	 */
	private $dir_tmpl; //директория хранения tpl файлов

	/**
	 * View constructor.
	 * @param $dir_tmpl
	 */
	public function __construct($dir_tmpl)
	{
		$this->dir_tmpl = $dir_tmpl;
	}

	/**
	 * @param $file
	 * @param $params
	 * @param bool $return
	 * @return false|string
	 */
	public function render($file, $params, $return = false)
	{
		$template = $this->dir_tmpl . $file . ".tpl";
		extract($params); //извлекаем параметры из массива
		ob_start(); //собираем в буфер
		include($template);
		if ($return) return ob_get_clean();
		else echo ob_get_clean();
	}
}

?>
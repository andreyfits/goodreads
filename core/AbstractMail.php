<?php

/**
 * Class AbstractMail responsible for sending email messages to mail services
 */
abstract class AbstractMail
{
	/**
	 * @var
	 */
	private $view; //шаблонизатор
	/**
	 * @var string
	 */
	private $from; //с какого email
	/**
	 * @var string
	 */
	private $from_name = ""; //имя отправителя
	/**
	 * @var string
	 */
	private $type = "text/html"; //тип письма
	/**
	 * @var string
	 */
	private $encoding = "utf-8"; //кодировка письма

	/**
	 * AbstractMail constructor.
	 * @param $view
	 * @param $from
	 */
	public function __construct($view, $from)
	{
		$this->view = $view;
		$this->from = $from;
	}

	/**
	 * @param $from
	 */
	public function setFrom($from)
	{
		$this->from = $from;
	}

	/**
	 * @param $from_name
	 */
	public function setFromName($from_name)
	{
		$this->from_name = $from_name;
	}

	/**
	 * @param $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * Setting encoding
	 * @param $encoding
	 */
	public function setEncoding($encoding)
	{
		$this->encoding = $encoding;
	}

	/**
	 * Sending letter
	 * @param $to
	 * @param $data
	 * @param $template
	 * @return bool
	 */
	public function send($to, $data, $template)
	{
		$from = "=?utf-8?B?" . base64_encode($this->from_name) . "?=" . " <" . $this->from . ">"; //перекодируем
		$headers = "From: " . $from . "\r\nReply-To: " . $from . "\r\nContent-type: " . $this->type . "; charset=\"" . $this->encoding . "\"\r\n";
		$text = $this->view->render($template, $data, true); //получаем текст письма
		$lines = preg_split("/\\r\\n?|\\n/", $text); //начинаем парсить(разделять текст)
		$subject = $lines[0];
		$subject = "=?utf-8?B?" . base64_encode($subject) . "?=";
		$body = "";
		for ($i = 1; $i < count($lines); $i++) { //формируем текст письма
			$body .= $lines[$i]; //собираем воедино
			if ($i != count($lines) - 1) $body .= "\n"; //после каждой итерации, за искл. последней мы доб. переход на новую строку
		}
		if ($this->type = "text/html") $body = nl2br($body); //если html, а не текстовый, мы заменяем переходы на br
		return mail($to, $subject, $body, $headers);
	}

}

?>
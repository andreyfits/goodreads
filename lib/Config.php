<?php

/**
 * Class Config
 */
abstract class Config
{
	const SITENAME = "Goodreads.by";
	const SECRET = "DGLJDG5"; //секретное слово
	const ADDRESS = "http://goodreads.by"; //виртуальный адрес
	const ADM_NAME = "Andrey Fits"; //имя администратора(будет использоваться при отправке e-mail сообщения)
	const ADM_EMAIL = "admin@goodreads.by"; //email админа

	//данные для подключения к БД

	const API_KEY = "DKEL39DL";

	const DB_HOST = "localhost";
	const DB_USER = "root";
	const DB_PASSWORD = "";
	const DB_NAME = "goodreads";
	const DB_PREFIX = "xyz_";
	const DB_SYM_QUERY = "?";

	//путь к дерикториям

	const DIR_IMG = "images/";
	const DIR_IMG_ARTICLES = "images/articles/";
	const DIR_AVATAR = "images/avatars/";
	const DIR_TMPL = "tmpl/";
	const DIR_EMAILS = "tmpl/emails/";

	const LAYOUT = "main";
	const FILE_MESSAGES = "text/messages.ini";

	const FORMAT_DATE = "%d.%m.%Y %H:%M:%S";

	const COUNT_BOOKS_ON_PAGE = 3;
	const COUNT_SHOW_PAGES = 10;

	const MIN_SEARCH_LEN = 3;
	const LEN_SEARCH_RES = 255;

	const SEF_SUFFIX = ".html";

	const DEFAULT_AVATAR = "default.png";
	const MAX_SIZE_AVATAR = 2560000;
}

?>

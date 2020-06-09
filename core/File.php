<?php

/**
 * Class File
 */
class File
{
	/**
	 * Upload file
	 * @param $file
	 * @param $max_size
	 * @param $dir
	 * @param bool $root
	 * @param bool $source_name
	 * @return mixed|string
	 * @throws Exception
	 */
	public static function uploadIMG($file, $max_size, $dir, $root = false, $source_name = false)
	{
		$blacklist = array(".php", ".phtml", ".php3", ".php4", ".html", ".htm");
		foreach ($blacklist as $item)
			if (preg_match("/$item\$/i", $file["name"])) throw new Exception("ERROR_AVATAR_TYPE"); //если у нас в имени файла одно из вышестоящих изображений
		$type = $file["type"];
		$size = $file["size"];
		if (($type != "image/jpg") && ($type != "image/jpeg") && ($type != "image/gif") && ($type != "image/png")) throw new Exception("ERROR_AVATAR_TYPE");
		if ($size > $max_size) throw new Exception("ERROR_AVATAR_SIZE");
		if ($source_name) $avatar_name = $file["name"];
		else $avatar_name = self::getName() . "." . substr($type, strlen("image/")); //формируем тип расширения
		$upload_file = $dir . $avatar_name; //получаем полный путь картинки
		if (!$root) $upload_file = $_SERVER["DOCUMENT_ROOT"] . $upload_file; //если не корневая, то мы получаем кор. дерикторию
		if (!move_uploaded_file($file["tmp_name"], $upload_file)) throw new Exception("UNKNOWN_ERROR"); //загр. на сервер
		return $avatar_name;
	}

	/**
	 * @return string
	 */
	public static function getName()
	{
		return uniqid(); //генер. случайный ключ
	}

	/**
	 * File deletion
	 * @param $file
	 * @param bool $root
	 */
	public static function delete($file, $root = false)
	{
		if (!$root) $file = $_SERVER["DOCUMENT_ROOT"] . $file; //если не корн. дериктория, то мы ее формируем
		if (file_exists($file)) unlink($file); //если файл сущ., то мы его удаляем
	}

	/**
	 * Checking the file on the server
	 * @param $file
	 * @param bool $root
	 * @return bool
	 */
	public static function isExists($file, $root = false)
	{
		if (!$root) $file = $_SERVER["DOCUMENT_ROOT"] . $file;
		return file_exists($file);
	}
}

?>
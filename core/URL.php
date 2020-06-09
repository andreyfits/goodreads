<?php

/**
 * Class URL
 */
class URL
{
	/**
	 * Generating URL
	 * @param $action
	 * @param string $controller
	 * @param array $data
	 * @param bool $amp
	 * @param string $address
	 * @param bool $handler
	 * @return false|string
	 */
	public static function get($action, $controller = "", $data = array(), $amp = true, $address = "", $handler = true)
	{
		if ($amp) $amp = "&amp;"; //если амперсанд передан
		else $amp = "&";
		if ($controller) $uri = "/$controller/$action"; //если у нас есть контроллер
		else $uri = "/$action"; //если контроллер не передан, то по умол. action
		if (count($data) != 0) {
			$uri .= "?";
			foreach ($data as $key => $value) {
				$uri .= "$key=$value" . $amp;
			}
			$uri = substr($uri, 0, -strlen($amp)); //убираем лишние амперсанды из запроса
		}
		if ($handler) return self::postHandler($uri, $address);
		return self::getAbsolute($address, $uri);
	}

	/**
	 * Getting the full address
	 * @param $address
	 * @param $uri
	 * @return string
	 */
	public static function getAbsolute($address, $uri)
	{
		return $address . $uri;
	}

	/**
	 * Getting the current url
	 * @param string $address
	 * @param bool $amp
	 * @return string|string[]
	 */
	public static function current($address = "", $amp = false)
	{
		$url = self::getAbsolute($address, $_SERVER["REQUEST_URI"]);
		if ($amp) $url = str_replace("&", "&amp;", $url);
		return $url;
	}

	/**
	 * Getting controller
	 * @return array|string[]
	 */
	public static function getControllerAndAction()
	{
		$uri = $_SERVER["REQUEST_URI"];
		$uri = UseSEF::getRequest($uri);
		if (!$uri) return array("Main", "404");
		list($url_part, $qs_part) = array_pad(explode("?", $uri), 2, ""); //фун-ция доп. массив 2 элем. с пустым значением
		parse_str($qs_part, $qs_vars); //разбираем строку
		Request::addSEFData($qs_vars);
		$controller_name = "Main";
		$action_name = "index";
		if (($pos = strpos($uri, "?")) !== false) $uri = substr($uri, 0, strpos($uri, "?"));
		$routes = explode("/", $uri); //разбиваем по /
		if (!empty($routes[2])) {
			if (!empty($routes[1])) $controller_name = $routes[1];
			$action_name = $routes[2];
		} elseif (!empty($routes[1])) $action_name = $routes[1];
		return array($controller_name, $action_name);
	}

	/**
	 * @param $url
	 * @param bool $amp
	 * @return false|string
	 */
	public static function deletePage($url, $amp = true)
	{
		return self::deleteGET($url, "page", $amp);
	}

	/**
	 * @param $url
	 * @param bool $amp
	 * @return false|string
	 */
	public static function addTemplatePage($url, $amp = true)
	{
		return self::addGET($url, "page", "", $amp);
	}

	/**
	 * @param $url
	 * @param $name
	 * @param $value
	 * @param bool $amp
	 * @return false|string
	 */
	public static function addGET($url, $name, $value, $amp = true)
	{
		if (strpos($url, "?") === false) $url = $url . "?" . $name . "=" . $value;
		else {
			if ($amp) $amp = "&amp;";
			else $amp = "&";
			$url = $url . $amp . $name . "=" . $value;
		}
		return self::postHandler($url);
	}

	/**
	 * @param $url
	 * @param $name
	 * @param bool $amp
	 * @return false|string
	 */
	public static function deleteGET($url, $name, $amp = true)
	{
		$url = str_replace("&amp;", "&", $url);
		list($url_part, $qs_part) = array_pad(explode("?", $url), 2, "");
		parse_str($qs_part, $qs_vars); //парсим строку, чтобы получ. сами переменными с их значениями
		unset($qs_vars[$name]);
		if (count($qs_vars) != 0) {
			$url = $url_part . "?" . http_build_query($qs_vars);
			if ($amp) $url = str_replace("&", "&amp;", $url);
		} else $url = $url_part;
		return self::postHandler($url);
	}

	/**
	 * @param $url
	 * @param $id
	 * @return string
	 */
	public static function addID($url, $id)
	{
		return $url . "#" . $id;
	}

	/**
	 * @param $uri
	 * @param string $address
	 * @return false|string
	 */
	private static function postHandler($uri, $address = "")
	{
		$uri = UseSEF::replaceSEF($uri, $address);
		return $uri;
	}

}

?>
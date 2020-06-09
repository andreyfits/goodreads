<?php

/**
 * Class Route
 */
class Route
{
	/**
	 * Running routers
	 */
	public static function start()
	{
		$ca_names = URL::getControllerAndAction(); //начинаем получать имена контроллера и экшена
		$controller_name = $ca_names[0] . "Controller"; //вызываем контроллер
		$action_name = "action" . $ca_names[1]; //вызываем экшн
		try {
			if (class_exists($controller_name)) $controller = new $controller_name(); //если класс сущ., то создаем экземпляр
			if (method_exists($controller, $action_name)) $controller->$action_name(); //если сущ. метод, то вызываем экшн нэйм
			else throw new Exception(); //генерируем исключение
		} catch (Exception $e) {
			if ($e->getMessage() != "ACCESS_DENIED") $controller->action404(); //делаем проверку исключения, для того чтобы отличить закрытие доступа к страницы от несущ. страницы
		}
	}

}

?>
<?php

/**
 * Class Captcha
 */
class Captcha
{
	/**
	 * Width captcha
	 */
	const WIDTH = 100;
	/**
	 * Height captcha
	 */
	const HEIGHT = 60;
	/**
	 * Font size
	 */
	const FONT_SIZE = 16;
	/**
	 * Count letters and numbers
	 */
	const LET_AMOUNT = 4;
	/**
	 * Count symbols
	 */
	const BG_LET_AMOUNT = 30;
	/**
	 * Font
	 */
	const FONT = "fonts/verdana.ttf";

	/**
	 * @var string[]
	 */
	private static $letters = array("a", "b", "c", "d", "e", "f", "g"); //буквы, кот. будут выводится
	/**
	 * @var int[]
	 */
	private static $colors = array(90, 110, 130, 150, 170, 190, 210);

	/**
	 * Generating captcha
	 */
	public static function generate()
	{
		if (!session_id()) session_start(); //если сессия была не начата, то мы ее начинаем
		$src = imagecreatetruecolor(self::WIDTH, self::HEIGHT); //создаем изобр.
		$bg = imagecolorallocate($src, 255, 255, 255); //цвет фона белый
		imagefill($src, 0, 0, $bg); //заливаем капчу

		for ($i = 0; $i < self::BG_LET_AMOUNT; $i++) { //создаем на капчу шум
			$color = imagecolorallocatealpha($src, rand(0, 255), rand(0, 255), rand(0, 255), 100); //ген. прозрачный цвет
			$letter = self::$letters[rand(0, count(self::$letters) - 1)]; //ген. букву
			$size = rand(self::FONT_SIZE - 2, self::FONT_SIZE + 2); //ген. размер
			imagettftext($src, $size, rand(0, 45), rand(self::WIDTH * 0.1, self::WIDTH * 0.9), rand(self::HEIGHT * 0.1, self::HEIGHT * 0.9), $color, self::FONT, $letter);
		}
		//выводим капчу при регистрации
		$code = "";
		for ($i = 0; $i < self::LET_AMOUNT; $i++) {
			$color = imagecolorallocatealpha($src, self::$colors[rand(0, count(self::$colors) - 1)], //берем случ. эл. из нашего массива
				self::$colors[rand(0, count(self::$colors) - 1)],
				self::$colors[rand(0, count(self::$colors) - 1)], rand(20, 40));
			$letter = self::$letters[rand(0, count(self::$letters) - 1)];
			$size = rand(self::FONT_SIZE * 2 - 2, self::FONT_SIZE * 2 + 2); //увел. текст примерно в два раза
			$x = ($i + 1) * self::FONT_SIZE + rand(1, 5); //вычисл. координаты x и y, кот. будут идти последовательно
			$y = ((self::HEIGHT * 2) / 3) + rand(0, 5); //выводим по высоте
			imagettftext($src, $size, rand(0, 15), $x, $y, $color, self::FONT, $letter);  //берем произвольный угол наклона (0,15)
			$code .= $letter;
		}
		$_SESSION["rand_code"] = $code; //сохр. код в сессию
		header("Content-type: image/gif"); //отправляем в заголовок, и будем выводить в опред. формате
		imagegif($src);
	}

	/**
	 * Checking captcha
	 * @param $code
	 * @return bool
	 */
	public static function check($code)
	{
		if (!session_id()) session_start(); //если сессия была не начата, то мы ее начинаем
		return ($code === $_SESSION["rand_code"]);
	}
}

?>
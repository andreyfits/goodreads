<?php

/**
 * Class UserDB
 */
class UserDB extends ObjectDB
{
	/**
	 * @var string
	 */
	protected static $table = "users";
	/**
	 * @var null
	 */
	private $new_password = null;

	/**
	 * UserDB constructor.
	 */
	public function __construct()
    {
        parent::__construct(self::$table);
        $this->add("login", "ValidateLogin");
        $this->add("email", "ValidateEmail");
        $this->add("password", "ValidatePassword");
        $this->add("name", "ValidateName");
        $this->add("avatar", "ValidateIMG");
        $this->add("date_reg", "ValidateDate", self::TYPE_TIMESTAMP, $this->getDate());
        $this->add("activation", "ValidateActivation", null, $this->getKey());
    }

	/**
	 * @param $password
	 */
	public function setPassword($password)
    {
        $this->new_password = $password;
    }

	/**
	 * @return |null
	 */
	public function getPassword()
    {
        return $this->new_password;
    }

	/**
	 * @param $email
	 * @return bool
	 */
	public function loadOnEmail($email)
    {
        return $this->loadOnField("email", $email);
    }

	/**
	 * @param $login
	 * @return bool
	 */
	public function loadOnLogin($login)
    {
        return $this->loadOnField("login", $login);
    }

	/**
	 * @return bool
	 */
	protected function postInit()
    {
        if (is_null($this->avatar)) $this->avatar = Config::DEFAULT_AVATAR; //если автара нет
        $this->avatar = Config::DIR_AVATAR . $this->avatar; //указываем путь к аватарке
        return true;
    }

	/**
	 * @return bool
	 */
	protected function preValidate()
    {
        if ($this->avatar == Config::DIR_AVATAR . Config::DEFAULT_AVATAR) $this->avatar = null;
        if (!is_null($this->avatar)) $this->avatar = basename($this->avatar); //если автар не пустой, то зашифр.
        if (!is_null($this->new_password)) $this->password = $this->new_password; //если нов. параль не пустой, то меняем
        return true;
    }

	/**
	 * @return bool
	 */
	protected function postValidate()
    {
        if (!is_null($this->new_password)) $this->password = self::hash($this->new_password, Config::SECRET); //обычный пароль преобразовать в хэш, перед отправкой в базу
        return true;
    }

	/**
	 * @return bool
	 */
	public function login()
    {
        if ($this->activation != "") return false;
        if (!session_id()) session_start(); //если сессия не была начата, то начинаем
        $_SESSION["auth_login"] = $this->login; //записываем в сессию логин
        $_SESSION["auth_password"] = $this->password; //записываем в сессию пароль
    }

	/**
	 *
	 */
	public function logout()
    {
        if (!session_id()) session_start(); //если сессия не была начата, то начинаем
        unset($_SESSION["auth_login"]); //удаляем сессию с логином
        unset($_SESSION["auth_password"]); //удаляем сессию с паролем
    }

	/**
	 * @return string|null
	 */
	public function getAvatar()
    {
        $avatar = basename($this->avatar);
        if ($avatar != Config::DEFAULT_AVATAR) return $avatar;
        return null;
    }

	/**
	 * @param $password
	 * @return bool
	 */
	public function checkPassword($password)
    {
        return $this->password === self::hash($password, Config::SECRET);
    }

	/**
	 * @param bool $login
	 * @param bool $password
	 * @return UserDB|void
	 * @throws Exception
	 */
	public static function authUser($login = false, $password = false)
    {
        if ($login) $auth = true; //если передается логин
        else { //иначе идет проверка в сессии
            if (!session_id()) session_start(); //если сессия не была начата, то начинаем
            if (!empty($_SESSION["auth_login"]) && !empty($_SESSION["auth_password"])) { //если у нас не пустые данные у массивов
                $login = $_SESSION["auth_login"]; //данные логина берем из сессии
                $password = $_SESSION["auth_password"]; //данные пароля берем из сессии
            } else return;
            $auth = false;
        }
        $user = new UserDB();
        if ($auth) $password = self::hash($password, Config::SECRET); //если эта была авторизация, то переданный пароль мы должны захэшировать
        $select = new Select(); //нахадим пол-ля, который должен удовлетворять нашим запросам
        $select->from(self::$table, array("COUNT(id)"))
            ->where("`login` = " . self::$db->getSQ(), array($login))
            ->where("`password` = " . self::$db->getSQ(), array($password));
        $count = self::$db->selectCell($select); //ищем пол-лей
        if ($count) {
            $user->loadOnLogin($login); //нач. его загр. по логину
            if ($user->activation != "") throw new Exception("ERROR_ACTIVATE_USER"); //если пол-ль не активирован, то генер. ошибку
            if ($auth) $user->login();
            return $user;
        }
        if ($auth) throw new Exception("ERROR_AUTH_USER"); //если не нашли пол-ля с логином и паролем, то генер. ошибку
    }

	/**
	 * @return string
	 */
	public function getSecretKey()
    {
        return self::hash($this->email . $this->password, Config::SECRET);
    }

}

?>
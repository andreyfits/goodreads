<?php

/**
 * The implementation class UserController of the user profile
 */
class UserController extends Controller
{
	/**
	 * Editing a profile
	 */
	public function actionEditProfile()
	{
		$message_avatar_name = "avatar"; //загр. автарки
		$message_name_name = "name"; //смена имени
		$message_password_name = "password"; //смена пароля

		if ($this->request->change_avatar) { //проверка зар. аватара
			$img = $this->fp->uploadIMG($message_avatar_name, $_FILES["avatar"], Config::MAX_SIZE_AVATAR, Config::DIR_AVATAR);
			if ($img) {
				$tmp = $this->auth_user->getAvatar(); //обновляем данные у пол-ля
				$obj = $this->fp->process($message_avatar_name, $this->auth_user, array(array("avatar", $img)), array(), "SUCCESS_AVATAR_CHANGE");
				if ($obj instanceof UserDB) {
					if ($tmp) {
						File::delete(Config::DIR_AVATAR . $tmp);
					}
					$this->redirect(URL::current());
				}
			}
		} elseif ($this->request->change_name) { //проверка изменения имени
			$checks = array(array($this->auth_user->checkPassword($this->request->password_current_name), true, "ERROR_PASSWORD_CURRENT"));
			$user_temp = $this->fp->process($message_name_name, $this->auth_user, array("name"), $checks, "SUCCESS_NAME_CHANGE");
			if ($user_temp instanceof UserDB) {
				$this->redirect(URL::current());
			}
		} elseif ($this->request->change_password) { //проверка изменения пароля
			$checks = array(
				array(
					$this->auth_user->checkPassword($this->request->password_current),
					true,
					"ERROR_PASSWORD_CURRENT"
				)
			); //проверяем правильно ли введен текущий пароль
			$checks[] = array($this->request->password, $this->request->password_conf, "ERROR_PASSWORD_CONF");
			$user_temp = $this->fp->process($message_password_name, $this->auth_user, array(
				array(
					"setPassword()",
					$this->request->password
				)
			), $checks, "SUCCESS_PASSWORD_CHANGE");
			if ($user_temp instanceof UserDB) {
				$this->auth_user->login();
				$this->redirect(URL::current());
			}
		}

		$this->title = "Редактирование профиля";
		$this->meta_desc = "Редактирование профиля пользователя.";
		$this->meta_key = "редактирование профиля, редактирование профиля пользователя, редактирование профиля пользователя сайт";

		$form_avatar = new Form(); //форма для загр. аватарки
		$form_avatar->name = "change_avatar";
		$form_avatar->action = URL::current();
		$form_avatar->enctype = "multipart/form-data"; //загр. файла
		$form_avatar->message = $this->fp->getSessionMessage($message_avatar_name); //загр. ошибки, если были
		$form_avatar->file("avatar", "Аватар:");
		$form_avatar->submit("Сохранить");

		$form_avatar->addJSV("avatar", $this->jsv->avatar()); //проверка поля аватарки

		$form_name = new Form(); //форма для изменения имени
		$form_name->name = "change_name";
		$form_name->header = "Изменить имя";
		$form_name->action = URL::current();
		$form_name->message = $this->fp->getSessionMessage($message_name_name);
		$form_name->text("name", "Ваше имя:", $this->auth_user->name);
		$form_name->password("password_current_name", "Текущий пароль"); //именить имя можно, зная только пароль
		$form_name->submit("Сохранить");

		$form_name->addJSV("name", $this->jsv->name()); //проверка поля имени
		$form_name->addJSV("password_current_name", $this->jsv->password(false, false, "ERROR_PASSWORD_CURRENT_EMPTY")); //проверка поля пароля

		$form_password = new Form(); //форма для изменения пароля
		$form_password->name = "change_password";
		$form_password->header = "Изменить пароль";
		$form_password->action = URL::current();
		$form_password->message = $this->fp->getSessionMessage($message_password_name);
		$form_password->password("password", "Новый пароль");
		$form_password->password("password_conf", "Повторите пароль");
		$form_password->password("password_current", "Текущий пароль");
		$form_password->submit("Сохранить");


		$form_name->addJSV("password", $this->jsv->password("password_conf"));
		$form_name->addJSV("password_current", $this->jsv->password(false, false, "ERROR_PASSWORD_CURRENT_EMPTY"));
		$hornav = $this->getHornav();
		$hornav->addData("Редактирование профиля");

		$this->render($this->renderData(array(
			"hornav" => $hornav,
			"form_avatar" => $form_avatar,
			"form_name" => $form_name,
			"form_password" => $form_password
		), "profile", array("avatar" => $this->auth_user->avatar, "max_size" => (Config::MAX_SIZE_AVATAR / KB_B))));
	}

	/**
	 * Checking access
	 * @return bool
	 */
	protected function access()
	{
		if ($this->auth_user) { //если у нас есть авт. пол-ль
			return true;
		}

		return false;
	}

}

?>
<?php

/**
 * Class controller
 */
abstract class Controller extends AbstractController
{
	/**
	 * @var string
	 */
	protected $title;
	/**
	 * @var string
	 */
	protected $meta_desc;
	/**
	 * @var string
	 */
	protected $meta_key;
	/**
	 * @var Mail|null
	 */
	protected $mail = null;
	/**
	 * @var false|string
	 */
	protected $url_active;
	/**
	 * @var int
	 */
	protected $section_id = 0;

	/**
	 * Controller constructor.
	 * @throws Exception
	 */
	public function __construct()
	{
		parent::__construct(new View(Config::DIR_TMPL), new Message(Config::FILE_MESSAGES));
		$this->mail = new Mail();
		$this->url_active = URL::deleteGET(URL::current(), "page");
	}

	/**
	 * Return 404 page
	 * @return mixed|void
	 */
	public function action404()
	{
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		$this->title = "Страница не найдена - 404";
		$this->meta_desc = "Запрошенная страница не существует.";
		$this->meta_key = "страница не найдена, страница не существует, 404";

		$pm = new PageMessage();
		$pm->header = "Страница не найдена";
		$pm->text = "К сожалению, запрошенная страница не существует. Проверьте правильность ввода адреса.";
		$this->render($pm);
	}

	/**
	 * Checking access rights to the requested page
	 * @return mixed|void
	 */
	protected function accessDenied()
	{
		$this->title = "Доступ закрыт!";
		$this->meta_desc = "Доступ к данной странице закрыт.";
		$this->meta_key = "доступ закрыт, доступ закрыт страница, доступ закрыт страница 403";

		$pm = new PageMessage();
		$pm->header = "Доступ закрыт!";
		$pm->text = "У Вас нет прав доступа к данной странице.";
		$this->render($pm);
	}

	/**
	 * Return page
	 * @param $str
	 * @return mixed|void
	 */
	final protected function render($str)
	{
		$params = array();
		$params["header"] = $this->getHeader();
		$params["auth"] = $this->getAuth();
		$params["top"] = $this->getTop();
		$params["slider"] = $this->getSlider();
		$params["left"] = $this->getLeft();
		$params["right"] = $this->getRight();
		$params["center"] = $str;
		$params["link_search"] = URL::get("search");
		$this->view->render(Config::LAYOUT, $params);
	}

	/**
	 * Method header and enabling scripts
	 * @return Header
	 */
	protected function getHeader()
	{
		$header = new Header();
		$header->title = $this->title;
		$header->meta("Content-Type", "text/html; charset=utf-8", true);
		$header->meta("description", $this->meta_desc, false);
		$header->meta("keywords", $this->meta_key, false);
		$header->meta("viewport", "width=device-width", false);
		$header->favicon = "favicon.ico";
		$header->css = array("/styles/main.css", "/styles/prettify.css");
		$header->js = array("/js/jquery-1.10.2.min.js", "/js/functions.js", "/js/validator.js", "/js/prettify.js");

		return $header;
	}

	/**
	 * Authorization user
	 * @return Auth|string
	 */
	protected function getAuth()
	{
		if ($this->auth_user) {
			return "";
		}
		$auth = new Auth();
		$auth->message = $this->fp->getSessionMessage("auth");
		$auth->action = URL::current("", true);
		$auth->link_register = URL::get("register");
		$auth->link_reset = URL::get("reset");
		$auth->link_remind = URL::get("remind");

		return $auth;
	}

	/**
	 * Getting top menu
	 * @return TopMenu
	 */
	protected function getTop()
	{
		$items = MenuDB::getTopMenu();
		$topmenu = new TopMenu();
		$topmenu->uri = $this->url_active;
		$topmenu->items = $items;

		return $topmenu;
	}

	/**
	 * Getting slider
	 * @return Slider
	 * @throws Exception
	 */
	protected function getSlider()
	{
		$recommended = new RecommendedDB();
		$recommended->loadOnSectionID($this->section_id, PAY_RECOMMENDED);
		$slider = new Slider();
		$slider->recommended = $recommended;

		return $slider;
	}

	/**
	 * Getting the left side of the site
	 * @return string
	 */
	protected function getLeft()
	{
		$items = MenuDB::getMainMenu();
		$mainmenu = new MainMenu();
		$mainmenu->uri = $this->url_active;
		$mainmenu->items = $items;
		if ($this->auth_user) {
			$user_panel = new UserPanel(); //панель авт. юзера
			$user_panel->user = $this->auth_user;
			$user_panel->uri = $this->url_active;
			$user_panel->addItem("Редактировать профиль", URL::get("editprofile", "user"));
			$user_panel->addItem("Выход", URL::get("logout"));
		} else {
			$user_panel = "";
		}
		$poll_db = new PollDB();
		$poll_db->loadRandom();
		if ($poll_db->isSaved()) {
			$poll = new Poll();
			$poll->action = URL::get("poll", "", array("id" => $poll_db->id));
			$poll->title = $poll_db->title;
			$poll->data = PollDataDB::getAllOnPollID($poll_db->id);
		} else {
			$poll = "";
		}

		return $user_panel . $mainmenu . $poll;
	}


	/**
	 * Getting the right side of the site
	 * @return string
	 * @throws Exception
	 */
	protected function getRight()
	{
		$recommended_db_1 = new RecommendedDB();
		$recommended_db_1->loadOnSectionID($this->section_id, FREE_RECOMMENDED);
		$recommended_db_2 = new RecommendedDB();
		$recommended_db_2->loadOnSectionID($this->section_id, ONLINE_RECOMMENDED);
		$recommendeds = array($recommended_db_1, $recommended_db_2);

		$recommended = new Recommended();
		$recommended->recommendeds = $recommendeds;
		$recommended->auth_user = $this->auth_user;

		$quote_db = new QuoteDB();
		$quote_db->loadRandom();

		$quote = new Quote();
		$quote->quote = $quote_db;

		return $recommended . $quote;

	}

	/**
	 * @return Hornav
	 */
	protected function getHornav()
	{
		$hornav = new Hornav(); //вызываем модуль
		$hornav->addData("Главная", URL::get("")); //ссылка на главную страницу

		return $hornav;
	}

	/**
	 * @param $count_on_page
	 * @return float|int
	 */
	final protected function getOffset($count_on_page)
	{
		return $count_on_page * ($this->getPage() - 1);
	}

	/**
	 * @return int|mixed
	 */
	final protected function getPage()
	{
		$page = ($this->request->page) ? $this->request->page : 1;
		if ($page < 1) {
			$this->notFound();
		}

		return $page;
	}

	/**
	 * Getting pagination
	 * @param $count_elements
	 * @param $count_on_page
	 * @param bool $url
	 * @return Pagination
	 */
	final protected function getPagination($count_elements, $count_on_page, $url = false)
	{
		$count_pages = ceil($count_elements / $count_on_page);
		$active = $this->getPage();
		if (($active > $count_pages) && ($active > 1)) {
			$this->notFound();
		}
		$pagination = new Pagination();
		if (!$url) {
			$url = URL::deletePage(URL::current());
		}
		$pagination->url = $url;
		$pagination->url_page = URL::addTemplatePage($url);
		$pagination->count_elements = $count_elements;
		$pagination->count_on_page = $count_on_page;
		$pagination->count_show_pages = Config::COUNT_SHOW_PAGES;
		$pagination->active = $active;

		return $pagination;
	}

	/**
	 * @return bool|UserDB|null
	 */
	protected function authUser()
	{
		$login = "";
		$password = "";
		$redirect = false;
		if ($this->request->auth) { //если была отправлена форма регистрации
			$login = $this->request->login;
			$password = $this->request->password;
			$redirect = true;
		}
		$user = $this->fp->auth("auth", "UserDB", "authUser", $login, $password);
		if ($user instanceof UserDB) { //если пол-ль соответствует классу
			if ($redirect) {
				$this->redirect(URL::current());
			}

			return $user;
		}

		return null;
	}

}

?>
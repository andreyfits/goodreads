<?php

/**
 * Class AbstractController
 */
abstract class AbstractController
{
	/**
	 * @var
	 */
	protected $view;
	/**
	 * @var Request
	 */
	protected $request;
	/**
	 * @var FormProcessor|null
	 */
	protected $fp = null;
	/**
	 * @var null
	 */
	protected $auth_user = null;
	/**
	 * @var JSValidator|null
	 */
	protected $jsv = null;

	/**
	 * AbstractController constructor.
	 * @param $view
	 * @param $message
	 * @throws Exception
	 */
	public function __construct($view, $message)
	{
		if (!session_id()) session_start();
		$this->view = $view;
		$this->request = new Request();
		$this->fp = new FormProcessor($this->request, $message);
		$this->jsv = new JSValidator($message);
		$this->auth_user = $this->authUser();
		if (!$this->access()) {
			$this->accessDenied();
			throw new Exception("ACCESS_DENIED");
		}
	}

	/**
	 * @param $str
	 * @return mixed
	 */
	abstract protected function render($str);

	/**
	 * @return mixed
	 */
	abstract protected function accessDenied();

	/**
	 * @return mixed
	 */
	abstract protected function action404();

	/**
	 * @return null
	 */
	protected function authUser()
	{
		return null;
	}

	/**
	 * @return bool
	 */
	protected function access()
	{
		return true;
	}

	/**
	 * Return the page with the 404 response if the requested page was not found
	 */
	final protected function notFound()
	{
		$this->action404();
	}

	/**
	 * @param $url
	 */
	final protected function redirect($url)
	{
		header("Location: $url");
		exit;
	}

	/**
	 * @param $modules
	 * @param $layout
	 * @param array $params
	 * @return bool
	 */
	final protected function renderData($modules, $layout, $params = array())
	{
		if (!is_array($modules)) return false;
		foreach ($modules as $key => $value) {
			$params[$key] = $value;
		}
		return $this->view->render($layout, $params, true);
	}

}

?>
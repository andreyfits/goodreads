<?php

/**
 * Class CommentDB
 */
class CommentDB extends ObjectDB
{
	/**
	 * @var string
	 */
	protected static $table = "comments";

	/**
	 * CommentDB constructor.
	 */
	public function __construct()
	{
		parent::__construct(self::$table);
		$this->add("article_id", "ValidateID");
		$this->add("user_id", "ValidateID");
		$this->add("parent_id", "ValidateID");
		$this->add("text", "ValidateSmallText");
		$this->add("date", "ValidateDate", self::TYPE_TIMESTAMP, $this->getDate());
	}

	/**
	 * @return bool
	 */
	protected function postInit()
	{
		$this->link = URL::get("article", "", array("id" => $this->article_id), false, Config::ADDRESS);
		$this->link = URL::addID($this->link, "comment_" . $this->id);
		return true;
	}

	/**
	 * Getting all comments inside a specific article
	 * @param $article_id
	 * @return array
	 * @throws Exception
	 */
	public static function getAllOnArticleID($article_id)
	{
		$select = new Select(self::$db);
		$select->from(self::$table, "*")
			->where("`article_id` = " . self::$db->getSQ(), array($article_id))
			->order("date");
		$comments = ObjectDB::buildMultiple(__CLASS__, self::$db->select($select));
		$comments = ObjectDB::addSubObject($comments, "UserDB", "user", "user_id");
		return $comments;
	}

	/**
	 * Count of comments on this article
	 * @param $article_id
	 * @return mixed
	 */
	public static function getCountOnArticleID($article_id)
	{
		$select = new Select(self::$db);
		$select->from(self::$table, array("COUNT(id)"))
			->where("`article_id` = " . self::$db->getSQ(), array($article_id));
		return self::$db->selectCell($select);
	}

	/**
	 * @param $auth_user
	 * @param $field
	 * @return bool
	 */
	public function accessEdit($auth_user, $field)
	{
		if ($field == "text") {
			return $this->user_id == $auth_user->id;
		}
		return false;
	}

	/**
	 * @param $auth_user
	 * @return bool
	 */
	public function accessDelete($auth_user)
	{
		return $this->user_id == $auth_user->id;
	}

	/**
	 * @param $parent_id
	 * @return array
	 */
	private static function getAllOnParentID($parent_id)
	{
		return self::getAllOnField(self::$table, __CLASS__, "parent_id", $parent_id);
	}

	/**
	 * @return bool
	 */
	protected function preDelete()
	{
		$comments = CommentDB::getAllOnParentID($this->id);
		foreach ($comments as $comment) {
			try {
				$comment->delete();
			} catch (Exception $e) {
				return false;
			}
		}
		return true;
	}

}

?>
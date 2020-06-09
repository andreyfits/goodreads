<?php

/**
 * Class PollVoterDB
 */
class PollVoterDB extends ObjectDB
{
	/**
	 * @var string
	 */
	protected static $table = "poll_voters";

	/**
	 * PollVoterDB constructor.
	 */
	public function __construct()
	{
		parent::__construct(self::$table);
		$this->add("poll_data_id", "ValidateID");
		$this->add("ip", "ValidateIP", self::TYPE_IP, $this->getIP());
		$this->add("date", "ValidateDate", self::TYPE_TIMESTAMP, $this->getDate());
	}

	/**
	 * @param $poll_data_id
	 * @return mixed
	 */
	public static function getCountOnPollDataID($poll_data_id)
	{
		return ObjectDB::getCountOnField(self::$table, "poll_data_id", $poll_data_id);
	}

	/**
	 * Voting verification
	 * @param $poll_data_ids
	 * @return bool
	 */
	public static function isAlreadyPoll($poll_data_ids)
	{
		$select = new Select(self::$db);
		$select->from(self::$table, array("id"))
			->whereIn("poll_data_id", $poll_data_ids)
			->where("`ip` = " . self::$db->getSQ(), array(ip2long($_SERVER["REMOTE_ADDR"])))
			->limit(1);
		return (self::$db->selectCell($select)) ? true : false;
	}

}

?>
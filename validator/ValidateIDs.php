<?php

/**
 * Class ValidateIDs
 */
class ValidateIDs extends Validator
{
	/**
	 * @return mixed|void
	 */
	protected function validate()
	{
		$data = $this->data;
		if (is_null($data)) return;
		if (!preg_match("/^\d+(,\d+)*\d?$/", $data)) $this->setError(self::CODE_UNKNOWN);
	}

}

?>
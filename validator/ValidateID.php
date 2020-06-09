<?php

/**
 * Class ValidateID
 */
class ValidateID extends Validator
{
	/**
	 * @return mixed|void
	 */
	protected function validate()
	{
		$data = (int)$this->data;
		if (!is_null($data) && ((!is_int($data)) || ($data < 0))) $this->setError(self::CODE_UNKNOWN);
	}

}

?>
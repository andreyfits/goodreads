<?php

/**
 * Class ValidateBoolean
 */
class ValidateBoolean extends Validator
{
	/**
	 * @return mixed|void
	 */
	protected function validate()
	{
		$data = $this->data;
		if (($data != 0) && ($data != 1)) $this->setError(self::CODE_UNKNOWN);
	}

}

?>
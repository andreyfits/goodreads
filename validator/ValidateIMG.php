<?php

/**
 * Class ValidateIMG
 */
class ValidateIMG extends Validator
{
	/**
	 * @return mixed|void
	 */
	protected function validate()
	{
		$data = $this->data;
		if (!is_null($data) && !preg_match("/^[a-z0-9-_]+\.(jpg|jpeg|png|gif)$/i", $data)) $this->setError(self::CODE_UNKNOWN);
	}

}

?>
<?php

/**
 * Class ValidateActivation
 */
class ValidateActivation extends Validator
{
	const MAX_LEN = 100;

	/**
	 * @return mixed|void
	 */
	protected function validate()
	{
		$data = $this->data;
		if (mb_strlen($data) > self::MAX_LEN) $this->setError(self::CODE_UNKNOWN);
	}
}

?>
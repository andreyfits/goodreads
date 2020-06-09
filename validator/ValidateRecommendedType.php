<?php

/**
 * Class ValidateRecommendedType
 */
class ValidateRecommendedType extends Validator
{
	const MAX_RECOMMENDEDTYPE = 3;

	/**
	 * @return mixed|void
	 */
	protected function validate()
	{
		$data = $this->data;
		if (!is_int($data)) $this->setError(self::CODE_UNKNOWN);
		else {
			if (($data < 1) || ($data > self::MAX_RECOMMENDEDTYPE)) $this->setError(self::CODE_UNKNOWN);
		}
	}

}

?>
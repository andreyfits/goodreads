<?php

/**
 * Class ValidateDate
 */
class ValidateDate extends Validator
{
	/**
	 * @return mixed|void
	 */
	protected function validate()
    {
        $data = $this->data;
        if (!is_null($data) && strtotime($data) === false) $this->setError(self::CODE_UNKNOWN);
    }

}

?>
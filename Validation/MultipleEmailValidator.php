<?php

namespace Mindy\Validation;

use Mindy\Locale\Translate;

/**
 * Class MultipleEmailValidator
 * @package Mindy\Validation
 */
class MultipleEmailValidator extends Validator
{
    /**
     * @param $value
     * @return mixed
     */
    public function validate($value)
    {
        $emails = explode(',', $value);
        $validator = new EmailValidator();
        foreach ($emails as $email) {
            if (!empty($email)) {
                if (!$validator->validate(trim($email))) {
                    $this->addError(Translate::getInstance()->t('validation', "{email} is not a valid email address", [
                        '{email}' => $email
                    ]));
                }
            }
        }
        return $this->hasErrors() === false;
    }
}

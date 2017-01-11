<?php

namespace Mindy\Validation;

use Mindy\Helper\Interfaces\Arrayable;
use Mindy\Locale\Translate;

/**
 * Class JsonValidator
 * @package Mindy\Validation
 */
class JsonValidator extends Validator
{
    public function validate($value)
    {
        if (is_object($value)) {
            if (!$value instanceof Arrayable) {
                $this->addError(Translate::getInstance()->t("validator", "Not json serialize object: {type}", [
                    '{type}' => gettype($value)
                ]));
            }
        }

        return $this->hasErrors() === false;
    }
}
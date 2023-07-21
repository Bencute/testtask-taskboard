<?php

namespace System\Validators;

trait Validators
{
    /**
     * @param string $attribute
     * @param mixed $value
     * @param array $params
     * @return ValidatorResponse
     */
    public function vRequire(string $attribute, $value, array $params = []): ValidatorResponse
    {
        if (is_null($value) || $value == '')
            return new ValidatorResponse(false, 'Поле обязательно для заполнения');

        return new ValidatorResponse(true);
    }

    /**
     * @param string $attribute
     * @param $value
     * @param array $params
     * @return ValidatorResponse
     */
    public function vEmail(string $attribute, $value, array $params = []): ValidatorResponse
    {
        if (!is_null($value) && $value != '' && filter_var($value, FILTER_VALIDATE_EMAIL) === false)
            return new ValidatorResponse(false, 'Неверный формат');

        return new ValidatorResponse(true);
    }
}
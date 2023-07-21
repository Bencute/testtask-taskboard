<?php

namespace System\Validators;

/**
 * Определяет ответ о состоянии валидности
 */
class ValidatorResponse
{
    /**
     * Валидный ли ответ
     */
    public bool $isValid;

    public ?string $message;

    public function __construct(bool $isValid, string $message = null)
    {
        $this->isValid = $isValid;
        $this->message = $message;
    }
}
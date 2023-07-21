<?php

namespace App\Helper;

class Html
{
    /**
     * @param bool|null $state
     * @return string
     */
    public static function stateInValidClass(?bool $state): string
    {
        return is_null($state) ? '' : ($state ? 'is-invalid' : 'is-valid');
    }

    /**
     * @param array $errorsMessage
     * @param string $glue
     * @return string
     */
    public static function getMessageErrors(array $errorsMessage, string $glue = '<br>'): string
    {
        return '<div class="invalid-feedback">' . implode($glue, $errorsMessage) . '</div>';
    }

    /**
     * @param bool $state
     * @return string
     */
    public static function boolToChecked(bool $state): string
    {
        return $state ? 'CHECKED' : '';
    }

    /**
     * Кодирует html сущности
     *
     * @param string $str
     * @return string
     */
    public static function encode(string $str): string
    {
        return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5);
    }
}
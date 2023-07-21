<?php

namespace System\Kernel;

abstract class Controller
{
    /**
     * Перенаправляет пользователя по переданному урл
     *
     * @param $url
     * @return string
     */
    public function redirect($url): string
    {
        header('Location: ' . $url);
        return '';
    }
}
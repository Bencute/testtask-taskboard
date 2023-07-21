<?php

namespace App\Helper;

class HtmlPagination extends Html
{
    public static function getLink(int $page, string $nameUrlParam = 'page'): string
    {
        $request = $_SERVER['REQUEST_URI'];
        $route = explode('?', $request)[0];

        $params = $_GET;
        unset($params[$nameUrlParam]);

        $linkParams = implode(
            '&',
            array_merge(
            /* array [name=>value] -> string "name=value" */
                array_map(
                    fn($name, $value) => $name . '=' . $value,
                    array_keys($params),
                    $params
                ),
                ($page === 1 ? [] : [$nameUrlParam . '=' . $page])
            )
        );

        if (strlen($linkParams)) {
            return $route . '?' . $linkParams;
        }
        return $route;
    }
}
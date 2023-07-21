<?php

namespace System\Kernel\Router;

class RouteAction
{
    private string $className;
    private string $actionName;
    private string|int|null $parameter;

    public function __construct(string $className, string $actionName, string|int|null $parameter = null)
    {
        $this->className = $className;
        $this->actionName = $actionName;
        $this->parameter = $parameter;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getActionName(): string
    {
        return $this->actionName;
    }

    public function getParameter(): int|string|null
    {
        return $this->parameter;
    }
}
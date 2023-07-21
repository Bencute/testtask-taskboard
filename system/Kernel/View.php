<?php

namespace System\Kernel;

use Exception;
use Throwable;

class View
{
    protected const  PATH_TO_APP = __DIR__ . '/../../app';

    protected string $pathToViews = 'view/default';
    protected string $pathToLayouts = 'view/layout';
    protected string $viewName;
    protected string $layout;
    private WebUser $user;

    public function __construct(string $viewName, string $layout)
    {
        $this->viewName = $viewName;
        $this->layout = $layout;
        $this->user = new WebUser;
    }

    /**
     * Возвращает путь до view файла
     */
    private function getPathToViewFile(): string
    {
        return $this->getBaseDir() . '/' . $this->pathToViews . '/' . $this->viewName . '.php';
    }

    private function getPathToLayoutFile(): string
    {
        return $this->getBaseDir() . '/' . $this->pathToLayouts . '/' . $this->layout . '.php';
    }

    /**
     * @throws Throwable
     */
    public function render(array $params = []): string
    {
        $contentView = $this->renderFile($this->getPathToViewFile(), $params);
        return $this->renderFile($this->getPathToLayoutFile(), ['content' => $contentView]);
    }

    /**
     * @throws Throwable
     */
    public function renderPartial(array $params = []): string
    {
        return $this->renderFile($this->getPathToViewFile(), $params);
    }

    /**
     * @throws Throwable
     */
    public function renderView(string $viewName, array $params = []): string
    {
        $view = new static($viewName, $this->layout);
        return $view->renderPartial($params);
    }

    /**
     * @throws Throwable
     */
    public function renderFile($file, array $params = []): string
    {
        $_obInitialLevel_ = ob_get_level();
        ob_start();
        ob_implicit_flush(false);
        extract($params, EXTR_OVERWRITE);
        try {
            require $file;
            $result = ob_get_clean();
            return $result === false ? '' : $result;
        } catch (Exception | Throwable $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        }
    }

    private function getBaseDir(): string
    {
        return self::PATH_TO_APP;
    }

    public function getUser(): WebUser
    {
        return $this->user;
    }
}
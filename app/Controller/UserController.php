<?php

namespace App\Controller;

use Exception;
use System\Kernel\View;
use Throwable;
use System\Kernel\WebUser;
use System\Kernel\Controller;

class UserController extends Controller
{
    private WebUser $user;

    public function __construct()
    {
        $this->user = new WebUser;
    }

    /**
     * @return string
     * @throws Throwable
     */
    public function login(): string
    {
        if (!$this->user->isGuest()) {
            $this->redirect('/');
            return '';
        }

        $login = '';
        $data = $_POST;
        if (isset($data['login'], $data['password'])) {
            $login = $data['login'];
            $password = $data['password'];
            if ($login === 'admin' && $password === '123') {
                if ($this->user->login(0)) {
                    $this->redirect('/');
                    return '';
                } else {
                    $this->user->addMessageError('Во время входа произошла ошибка');
                }
            } else {
                $this->user->addMessageError('Введенные данные не верны');
            }
        }

        return (new View('login', 'main'))->render([
            'login' => $login,
        ]);
    }

    /**
     * @throws Exception
     * @return string
     */
    public function logout(): string
    {
        $this->user->logout();
        $this->redirect('/');
        return '';
    }
}
<?php

namespace System\Kernel;

use Exception;

class SessionStorage implements StorageInterface
{
    /**
     * Ключ в массиве SESSION для хранения времени жизни сессии
     */
    const NAME_PARAM_DELETE_TIME = 'deleteTime';

    /**
     * Время жизни сессии на основе параметра NAME_PARAM_DELETE_TIME
     * В секундах
     */
    protected int $sessionTimeLeft = 86400;

    protected array $sessionStartParams = [
        'cookie_lifetime' => 86400, // 1 day
        'cookie_samesite' => 'Strict',
        'cookie_httponly' => true,
    ];

    /**
     * @throws Exception
     */
    public function __construct(array $config = [])
    {
        $this->sessionStartParams = array_merge($this->sessionStartParams, $config);
        @ini_set('session.use_strict_mode', 1);

        if (!$this->sessionInit())
            throw new Exception('Session cannot be start');
    }

    public function add(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public function isset(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function delete(string $key): void
    {
        if (isset($_SESSION[$key]))
            unset($_SESSION[$key]);
    }

    public function clear(): void
    {
        $_SESSION = [];
    }

    private function sessionInit(): bool
    {
        if (!$this->sessionStop()) {
            return false;
        }

        $result = $this->sessionStart();
        if ($result
            && !empty($_SESSION[self::NAME_PARAM_DELETE_TIME])
            && $_SESSION[self::NAME_PARAM_DELETE_TIME] < time() - $this->sessionTimeLeft)
        {
            return $this->sessionRegenerateId();
        }

        return $result;
    }

    public function sessionRegenerateId(): bool
    {
        if (!$this->sessionIsActive() && !$this->sessionStart())
            return false;

        $this->add(self::NAME_PARAM_DELETE_TIME, time());
        return session_regenerate_id();
    }

    private function sessionStop(): bool
    {
        if ($this->sessionIsActive()) {
            return session_write_close();
        }

        return true;
    }

    public function setLifetime(int $lifetime): bool
    {
        if (!$this->sessionStop())
            return false;

        $this->sessionStartParams['cookie_lifetime'] = $lifetime;
        return $this->sessionInit();
    }

    private function sessionIsActive(): bool
    {
        return session_status() == PHP_SESSION_ACTIVE;
    }

    private function sessionStart(): bool
    {
        return session_start($this->sessionStartParams);
    }
}
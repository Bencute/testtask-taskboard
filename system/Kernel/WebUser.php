<?php

namespace System\Kernel;

use Exception;

/**
 * Class WebUser
 * Класс представляет собой сущность пользователя который взаимодействует с приложением
 */
class WebUser
{
    /**
     * Время хранения хранилища данных в секундах до уничтожения по умолчанию
     */
    const DEFAULT_LIFETIME_STORAGE = 86400;

    /**
     * Время хранения авторизации в секундах по умолчанию
     * Если 0 то до закрытия браузера
     */
    const DEFAULT_LIFETIME_LOGIN = self::DEFAULT_LIFETIME_STORAGE;

    /**
     * Идентификатор в хоранилище имени параметра для идентификации пользователя
     */
    const PARAM_IS_AUTH = 'isAuth';
    const STORAGE_MESSAGE_SUCCESS_KEY = 'messageSuccess';
    const STORAGE_MESSAGE_ERROR_KEY = 'messageError';

    protected StorageInterface $storage;

    /**
     * Время хранения хранилища данных до уничтожения по умолчанию
     * В секундах
     *
     * @var int
     */
    protected int $lifetimeStorage;

    /**
     * @param int $lifetimeStorage
     * @throws Exception
     */
    public function __construct(int $lifetimeStorage = self::DEFAULT_LIFETIME_STORAGE)
    {
        $this->lifetimeStorage = $lifetimeStorage;
        $this->storage = new SessionStorage(['cookie_lifetime' => $this->lifetimeStorage]);
    }

    /**
     * $id идентификатор пользователя
     * $lifetime время хранения авторизации в секундах
     *
     * @param int $lifetime
     * @return bool
     */
    public function login(int $lifetime = self::DEFAULT_LIFETIME_LOGIN): bool
    {
        $storage = $this->getStorage();
        if (!$storage->setLifetime($lifetime)) {
            return false;
        }
        $storage->add(self::PARAM_IS_AUTH, true);
        return $storage->sessionRegenerateId();
    }

    /**
     * Снятие авторизации
     */
    public function logout(): bool
    {
        $this->getStorage()->delete(self::PARAM_IS_AUTH);
        return $this->getStorage()->sessionRegenerateId();
    }

    public function getStorage(): StorageInterface
    {
        return $this->storage;
    }

    /**
     * Возвращает авторизован ли пользователь
     *
     * @throws Exception
     */
    public function isGuest(): bool
    {
        return !$this->getStorage()->isset(self::PARAM_IS_AUTH)
            || !$this->getStorage()->get(self::PARAM_IS_AUTH);
    }

    /**
     * Добавляет сообщение пользователю
     *
     * @param string $message
     * @param string $key
     */
    public function addMessage(string $message, string $key): void
    {
        $flashes = $this->getStorage()->get($key, []);
        $flashes[] = $message;
        $this->getStorage()->add($key, $flashes);
    }

    /**
     * Получает сообщения
     *
     * @param string $key
     * @return array
     */
    public function getMessages(string $key): array
    {
        $flashes = $this->getStorage()->get($key, []);
        $this->getStorage()->add($key, []);

        return $flashes;
    }

    public function addMessageSuccess(string $message): void
    {
        $this->addMessage($message, self::STORAGE_MESSAGE_SUCCESS_KEY);
    }

    public function getMessagesSuccess(): array
    {
        return $this->getMessages(self::STORAGE_MESSAGE_SUCCESS_KEY);
    }

    public function addMessageError(string $message): void
    {
        $this->addMessage($message, self::STORAGE_MESSAGE_ERROR_KEY);
    }

    public function getMessagesError(): array
    {
        return $this->getMessages(self::STORAGE_MESSAGE_ERROR_KEY);
    }
}
<?php

namespace App\Models;

class Task
{
    protected ?int $id = null;

    protected string $name;

    protected string $email;

    protected string $content;

    protected bool $isDone;

    protected bool $isUpdated;

    /**
     * @param int|null $id
     * @param string $name
     * @param string $email
     * @param string $content
     * @param bool $isDone
     * @param bool $isUpdated
     */
    public function __construct(string $name,
                                string $email,
                                string $content,
                                bool $isDone = false,
                                bool $isUpdated = false,
                                ?int $id = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->content = $content;
        $this->isDone = $isDone;
        $this->isUpdated = $isUpdated;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @param bool $isDone
     */
    public function setIsDone(bool $isDone): void
    {
        $this->isDone = $isDone;
    }

    /**
     * @param bool $isUpdated
     */
    public function setIsUpdated(bool $isUpdated): void
    {
        $this->isUpdated = $isUpdated;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return bool
     */
    public function isDone(): bool
    {
        return $this->isDone;
    }

    /**
     * @return bool
     */
    public function isUpdated(): bool
    {
        return $this->isUpdated;
    }
}
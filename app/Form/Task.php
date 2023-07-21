<?php

namespace App\Form;

use System\Validators\Form;

class Task extends Form
{
    public string $name = '';
    public string $email = '';
    public string $content = '';
    public bool $done = false;

    /**
     * @inheritDoc
     */
    public function getRules(): array
    {
        return [
            ['email', 'vRequire'],
            ['email', 'vEmail'],
            ['name', 'vRequire'],
            ['content', 'vRequire'],
        ];
    }
}
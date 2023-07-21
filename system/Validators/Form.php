<?php

namespace System\Validators;

abstract class Form
{
    use Validators;

    /**
     * @var array
     */
    public array $errors = [];

    /**
     * @var array
     */
    public array $validatedAttributes = [];

    /**
     * Return format: [
     *      nameAttribute1, validatorMethod,
     *      nameAttribute2, validatorMethod,
     *      ...
     * ]
     *
     * @return array
     */
    abstract public function getRules(): array;

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $this->flushValidate();

        $rules = $this->getRules();
        foreach ($rules as $rule) {
            $nameAttribute = $rule[0];
            $nameRuleMethod = $rule[1];
            $ruleParams = $rule['params'] ?? [];

            $validateResponse = $this->$nameRuleMethod($nameAttribute, $this->$nameAttribute, $ruleParams);
            if (!$validateResponse->isValid) {
                $this->addError($nameAttribute, $validateResponse->message);
            }
            $this->addValidatedAttributes($nameAttribute);
        }

        return !$this->hasErrors();
    }

    /**
     * @param string $nameAttribute
     * @param string $message
     * @return bool
     */
    public function addError(string $nameAttribute, string $message): bool
    {
        $this->errors[$nameAttribute][] = $message;
        return true;
    }

    private function flushErrors(): void
    {
        $this->errors = [];
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * @param string $nameAttribute
     * @return bool|null
     */
    public function isAttributeError(string $nameAttribute): ?bool
    {
        // Проверка на участие атрибута в валидации
        if (array_search($nameAttribute, $this->getValidateAttributes()) === false)
            return null;

        // Проверка был ли провалидирован атрибут
        if (array_search($nameAttribute, $this->validatedAttributes) === false)
            return null;

        return isset($this->errors[$nameAttribute]);
    }

    public function getAttributeMessageErrors(string $nameAttribute): array
    {
        return $this->errors[$nameAttribute] ?? [];
    }

    public function getValidateAttributes(): array
    {
        return array_map(fn($rule) => $rule[0], $this->getRules());
    }

    public function addValidatedAttributes(string $nameAttribute): void
    {
        $this->validatedAttributes[] = $nameAttribute;
    }

    private function flushValidatedAttributes(): void
    {
        $this->validatedAttributes = [];
    }

    public function flushValidate(): void
    {
        $this->flushErrors();
        $this->flushValidatedAttributes();
    }
}
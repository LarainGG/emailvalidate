<?php
namespace App\Validator;

class EmailValidator {

    /**
     * @var string
     */
    private $email = null;

    public function __construct(string $email) {
        $this->email = $email;
    }

    public function validate(): bool
    {
        return $this->validateName() && $this->validateDomain();
    }

    private function validateName(): bool
    {
        return filter_var($this->email, FILTER_VALIDATE_EMAIL) === $this->email;
    }

    private function validateDomain(): bool
    {
        $position = strpos($this->email, '@');

        return $position > 0 && checkdnsrr(substr($this->email, $position + 1));
    }
}
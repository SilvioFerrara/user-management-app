<?php

class User {
    private string $name;
    private string $email;
    private string $birthdate;

    public function __construct(string $name, string $email, string $birthdate) {
        $this->name = $name;
        $this->email = $email;
        $this->birthdate = $birthdate;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getBirthdate(): string {
        return $this->birthdate;
    }
}

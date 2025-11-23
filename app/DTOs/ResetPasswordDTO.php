<?php

namespace App\DTOs;

class ResetPasswordDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly string $token
    ) {}

    /**
     * Create DTO from array
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            password: $data['password'],
            token: $data['token']
        );
    }

    /**
     * Convert DTO to array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
            'token' => $this->token,
        ];
    }
}

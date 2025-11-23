<?php

namespace App\Services;

use App\DTOs\ResetPasswordDTO;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PasswordResetService
{
    protected \App\Repositories\Contracts\UserRepositoryInterface $userRepository;

    public function __construct(
        \App\Repositories\Contracts\UserRepositoryInterface $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * Send password reset link
     *
     * @param string $email
     * @return string
     * @throws ValidationException
     */
    public function sendResetLink(string $email): string
    {
        $status = Password::sendResetLink(['email' => $email]);

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return __($status);
    }

    /**
     * Reset user password
     *
     * @param ResetPasswordDTO $dto
     * @return string
     * @throws ValidationException
     */
    public function resetPassword(ResetPasswordDTO $dto): string
    {
        $status = Password::reset(
            [
                'email' => $dto->email,
                'password' => $dto->password,
                'password_confirmation' => $dto->password,
                'token' => $dto->token,
            ],
            function ($user, $password) {
                $this->userRepository->resetPassword($user, $password);
                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return __($status);
    }
}

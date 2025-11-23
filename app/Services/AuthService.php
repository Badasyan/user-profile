<?php

namespace App\Services;

use App\DTOs\LoginUserDTO;
use App\DTOs\RegisterUserDTO;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * Register a new user
     *
     * @param RegisterUserDTO $dto
     * @return array
     */
    public function register(RegisterUserDTO $dto): array
    {
        $user = $this->userRepository->create($dto->toArray());

        $token = $user->createToken('auth_token')->accessToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Login user
     *
     * @param LoginUserDTO $dto
     * @return array
     * @throws ValidationException
     */
    public function login(LoginUserDTO $dto): array
    {
        $user = $this->userRepository->findByEmail($dto->email);

        if (!$user || !Hash::check($dto->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->accessToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Login user for web (session-based)
     *
     * @param LoginUserDTO $dto
     * @return User
     * @throws ValidationException
     */
    public function loginWeb(LoginUserDTO $dto): User
    {
        if (!Auth::attempt(['email' => $dto->email, 'password' => $dto->password], $dto->remember)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return Auth::user();
    }

    /**
     * Logout user (revoke token)
     *
     * @param User $user
     * @return void
     */
    public function logout(User $user): void
    {
        $user->token()->revoke();
    }

    /**
     * Logout user from web (session)
     *
     * @return void
     */
    public function logoutWeb(): void
    {
        Auth::logout();
    }
}

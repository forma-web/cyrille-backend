<?php

namespace App\Http\Controllers\V1;

use App\DTO\V1\UpdateUserDTO;
use App\Http\Requests\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Services\V1\UserService;

final class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    public function update(int $userId, UpdateUserRequest $request): UserResource
    {
        return UserResource::make(
            $this->userService->update($userId, UpdateUserDTO::fromRequest($request))
        );
    }

    public function current(): UserResource
    {
        return UserResource::make($this->userService->current());
    }
}

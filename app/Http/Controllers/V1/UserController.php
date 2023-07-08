<?php

namespace App\Http\Controllers\V1;

use App\DTO\V1\UpdateUserDTO;
use App\Http\Requests\V1\CheckOtpRequest;
use App\Http\Requests\V1\UpdateUserPasswordRequest;
use App\Http\Requests\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Services\V1\UserService;
use Illuminate\Http\Response;

final class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    public function update(UpdateUserRequest $request): UserResource
    {
        return UserResource::make(
            $this->userService->update(auth()->id(), UpdateUserDTO::fromRequest($request))
        );
    }

    public function updatePassword(UpdateUserPasswordRequest $request): Response
    {
        $password = $request->validated('password');

        $this->userService->updatePassword(auth()->id(), $password);

        return response()->noContent();
    }

    public function current(): UserResource
    {
        return UserResource::make($this->userService->current());
    }

    public function verify(CheckOtpRequest $request): Response
    {
        $code = $request->validated('code');

        $this->userService->verify($code);

        return response()->noContent();
    }
}

<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Http\FormRequest;

class AbstractRequest extends FormRequest
{
    protected array $availableIncludes = [];

    public function authorize(): bool
    {
        return true;
    }

    public function getLoggedUser(): Authenticatable
    {
        return Auth::User();
    }

    public function getLoggedUserId(): int
    {
        return Auth::id();
    }

    public function messages(): array
    {
        return [
        ];
    }

    public function getIncludes(): array
    {
        return $this->validated('includes') ?? [];
    }

    public function getFilters(): array
    {
        return $this->validated('filters') ?? [];
    }

    public function expectsJson(): bool
    {
        return true;
    }
}

<?php

namespace App\Http\Requests\User;


use App\Http\Requests\AbstractRequest;

class UpdateUserProfilePictureRequest extends AbstractRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'profile_picture' => 'required|file|image|mimes:jpg,png,jpeg|max:2048'
        ];
    }
}

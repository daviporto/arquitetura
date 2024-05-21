<?php

namespace App\Http\Requests\Auth;


use App\Domain\User\UserTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'password' => 'required|min:6|string',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|numeric|unique:users,phone',
            'type' => 'required|string|in:' . implode(',', array_column(UserTypeEnum::cases(), 'value')),
            'about_me' => 'string',
            'profile_picture' => 'file|image|mimes:jpg,png,jpeg|max:2048'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => trans('user.name.required'),
            'password.required' => trans('user.password.required'),
            'password.min' => trans('user.password.min'),
            'email.required' => trans('user.email.required'),
            'email.email' => trans('user.email.email'),
            'email.unique' => trans('user.email.unique'),
            'phone.required' => trans('user.phone.required'),
            'phone.unique' => trans('user.phone.unique'),
            'phone.numeric' => trans('user.phone.numeric'),
            'phone.max' => trans('user.phone.max'),
            'type.required' => trans('user.type.required'),
            'type.string' => trans('user.type.string'),
            'type.in' => trans('user.type.in'),
        ];
    }
}

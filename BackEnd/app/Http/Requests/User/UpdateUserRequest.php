<?php

namespace App\Http\Requests\User;


class UpdateUserRequest extends UserRequestHavingId
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
        ];
    }
}
<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseFormRequest;

class CreateUserRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|max:100',
            'confirm_password' => 'required|same:password',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email is required!',
            'name.required' => 'Name is required!',
            'password.required' => 'Password is required!',
            'surname.required' => 'Surname is required!',
            'nickname.required' => 'Nickname is required!',
            'role.required' => 'Role is required!',
            'confirm_password.required' => 'Password onfirmation is required!',
            'phone.required' => 'Phone is required!',
            'address.required' => 'Address is required!',
            'city.required' => 'City is required!',
            'state.required' => 'State is required!',
            'zip_code.required' => 'Zipcode is required!',
        ];
    }

    /**
     *  Filters to be applied to the input.
     *
     * @return array
     */
    public function filters()
    {
        return [
            'email' => 'trim|lowercase',
            'name' => 'trim|capitalize|escape'
        ];
    }
}

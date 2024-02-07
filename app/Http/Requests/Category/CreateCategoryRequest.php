<?php

namespace App\Http\Requests\Category;

use App\Http\Requests\BaseFormRequest;

class CreateCategoryRequest extends BaseFormRequest
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
            'name' => 'required|unique:categories|max:64',
        ];
    }

    public function messages()
    {
        return [
            'name' => 'Category is required!',
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
        ];
    }

}
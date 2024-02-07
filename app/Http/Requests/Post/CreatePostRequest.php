<?php

namespace App\Http\Requests\Post;

use App\Http\Requests\BaseFormRequest;

class CreatePostRequest extends BaseFormRequest
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
            'title' => 'required|max:64',
            'description' => 'required',
            'content' => 'required',
            'category_id' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png'
        ];
    }

    public function messages()
    {
        return [
            'title' => 'Title is required!',
            'description' => 'Description is required!',
            'content' => 'Content is required!',
            'category_id' => 'Category is required!',
            'image' => 'Post image is required!',
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
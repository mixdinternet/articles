<?php
namespace Mixdinternet\Articles\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEditArticlesRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => 'required'
            , 'star' => 'required'
            , 'name' => 'required|max:150'
            , 'description' => 'required'
            , 'published_at' => 'required|date_format:d/m/Y H:i'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

}
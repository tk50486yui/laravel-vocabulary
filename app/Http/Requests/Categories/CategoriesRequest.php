<?php
namespace App\Http\Requests\Categories;

use Illuminate\Foundation\Http\FormRequest;

class CategoriesRequest extends FormRequest
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
            'cate_name'      => 'required',
            'cate_parent_id' => 'sometimes|nullable|integer|min:1', // 外鍵
            'cate_level'     => 'sometimes|nullable|integer|min:1',
            'cate_order'     => 'sometimes|nullable|integer|min:0',
        ];
    }
}

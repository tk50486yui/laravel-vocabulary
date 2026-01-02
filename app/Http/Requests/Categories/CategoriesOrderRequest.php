<?php
namespace App\Http\Requests\Categories;

use Illuminate\Foundation\Http\FormRequest;

class CategoriesOrderRequest extends FormRequest
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
        /** [{"id":1, "cate_order": 0}, {"id":2, "cate_order": 0}...]
         *  陣列物件格式
         * **/
        return [
            '*.id'         => 'required|integer|min:1',
            '*.cate_order' => 'required|integer|min:0',
        ];
    }
}

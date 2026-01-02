<?php
namespace App\Http\Requests\Tags;

use Illuminate\Foundation\Http\FormRequest;

class TagsOrderRequest extends FormRequest
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
        /** [{"id":1, "ts_order": 0}, {"id":2, "ts_order": 0}...]
         *  陣列物件格式
         * **/
        return [
            '*.id'       => 'required|integer|min:1',
            '*.ts_order' => 'required|integer|min:0',
        ];
    }
}

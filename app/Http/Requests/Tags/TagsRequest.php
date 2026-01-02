<?php
namespace App\Http\Requests\Tags;

use Illuminate\Foundation\Http\FormRequest;

class TagsRequest extends FormRequest
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
            'ts_name'        => 'required',
            'ts_storage'     => 'sometimes|nullable|boolean',
            'ts_parent_id'   => 'sometimes|nullable|integer|min:1',
            'ts_level'       => 'sometimes|nullable|integer|min:1',
            'ts_order'       => 'sometimes|nullable|integer|min:0',
            'tc_id'          => 'sometimes|nullable|integer|min:1',
            'ts_description' => 'sometimes|nullable',
        ];
    }
}

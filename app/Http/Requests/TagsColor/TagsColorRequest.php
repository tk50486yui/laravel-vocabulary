<?php
namespace App\Http\Requests\TagsColor;

use Illuminate\Foundation\Http\FormRequest;

class TagsColorRequest extends FormRequest
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
            'tc_color'      => 'required',
            'tc_background' => 'required',
            'tc_border'     => 'required',
        ];
    }
}

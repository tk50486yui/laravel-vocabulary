<?php
namespace App\Http\Requests\WordsGroups;

use Illuminate\Foundation\Http\FormRequest;

class WordsGroupsRequest extends FormRequest
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
            'wg_name'              => 'required',
            /*
                "words_groups_details": []
                欲新增的資料 (ws_id) 依序放進 words_groups_details 就好
            */
            'words_groups_details' => 'sometimes|nullable|array',
        ];
    }
}

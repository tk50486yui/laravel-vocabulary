<?php
namespace App\Http\Requests\Articles;

use Illuminate\Foundation\Http\FormRequest;

class ArticlesRequest extends FormRequest
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
            'arti_title'    => 'required',
            'arti_content'  => 'sometimes',
            'arti_order'    => 'sometimes|nullable|integer|min:0',
            'cate_id'       => 'sometimes|nullable|integer|min:1', // 外鍵
            /*
                "articles_tags":{ "array" : [], "values": [] }
                欲新增的資料 (ts_id) 依序放進 "array" 就好
            */
            'articles_tags' => 'sometimes|nullable',
        ];
    }
}

<?php
namespace App\Services\Processors;

/**
 *  幫助 Service 處理較複雜的資料欄位
 **/

class WordsProcessor
{
    private $wordsTags;

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function populate($data)
    {
        $data['ws_name']          = $data['ws_name'] ?? null;
        $data['ws_definition']    = $data['ws_definition'] ?? null;
        $data['ws_pronunciation'] = $data['ws_pronunciation'] ?? null;
        $data['ws_slogan']        = $data['ws_slogan'] ?? null;
        $data['ws_description']   = $data['ws_description'] ?? null;
        $data['ws_is_important']  = $data['ws_is_important'] ?? null;
        $data['ws_is_common']     = $data['ws_is_common'] ?? null;
        $data['ws_forget_count']  = $data['ws_forget_count'] ?? null;
        $data['ws_order']         = $data['ws_order'] ?? null;
        $data['cate_id']          = $data['cate_id'] ?? null;

        return $this->trimData($data);
    }

    public function trimData($data)
    {

        $data['ws_name']          = $data['ws_name'] != null ? trim($data['ws_name']) : null;
        $data['ws_pronunciation'] = $data['ws_pronunciation'] != null ? trim($data['ws_pronunciation']) : null;

        return $data;
    }

    public function begin($data)
    {
        $this->validateWordsTags($data);
        if ($this->wordsTags == null) {
            return false;
        }
        return $this->FilterDupArray($this->wordsTags);
    }

    public function validateWordsTags($data)
    {
        if (isset($data['words_tags']['array']) && ! is_bool($data['words_tags']['array'])) {
            if (! is_array($data['words_tags']['array']) || empty($data['words_tags']['array'])) {
                $this->wordsTags = null;
            } else {
                $this->wordsTags = $data['words_tags']['array'];
            }
        } else {
            $this->wordsTags = null;
        }
    }

    public function FilterDupArray($new)
    {
        $output = [];
        $seen   = [];
        foreach ($new as $item) {
            // 避免重複資料
            if (! in_array($item, $seen)) {
                array_push($output, $item);
                array_push($seen, $item);
            }
        }

        return $output;
    }
}

<?php
namespace App\Services\Processors;

/**
 *  幫助 Service 處理較複雜的資料欄位
 **/

class ArticlesProcessor
{
    private $articlesTags;

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
        $data['arti_content'] = $data['arti_content'] ?? null;
        $data['arti_order']   = $data['arti_order'] ?? null;
        $data['cate_id']      = $data['cate_id'] ?? null;

        return $data;
    }

    public function begin($data)
    {
        $this->validateArticlesTags($data);
        if ($this->articlesTags == null) {
            return false;
        }
        return $this->FilterDupArray($this->articlesTags);
    }

    public function validateArticlesTags($data)
    {
        if (isset($data['articles_tags']['array']) && ! is_bool($data['articles_tags']['array'])) {
            if (! is_array($data['articles_tags']['array']) || empty($data['articles_tags']['array'])) {
                $this->articlesTags = null;
            } else {
                $this->articlesTags = $data['articles_tags']['array'];
            }
        } else {
            $this->articlesTags = null;
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

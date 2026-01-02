<?php
namespace App\Services\Processors;

/**
 *  幫助 Service 處理較複雜的資料欄位
 **/

class WordsGroupsProcessor
{
    private $wordsGroupsDetails;

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function begin($data)
    {
        $this->validateWordsGroupsDetails($data);
        if ($this->wordsGroupsDetails == null) {
            return false;
        }
        return $this->FilterDupArray($this->wordsGroupsDetails);
    }

    public function validateWordsGroupsDetails($data)
    {
        if (isset($data['words_groups_details']) && ! is_bool($data['words_groups_details'])) {
            if (! is_array($data['words_groups_details']) || empty($data['words_groups_details'])) {
                $this->wordsGroupsDetails = null;
            } else if (count($data['words_groups_details']) == 0) {
                $this->wordsGroupsDetails = null;
            } else {
                $this->wordsGroupsDetails = $data['words_groups_details'];
            }
        } else {
            $this->wordsGroupsDetails = null;
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

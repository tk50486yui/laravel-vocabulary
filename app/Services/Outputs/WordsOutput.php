<?php
namespace App\Services\Outputs;

/**
 *     輸出資料處理
 **/

class WordsOutput
{
    public function genWordsTags($result, $multiple = true)
    {
        if ($multiple) {
            $i = 0;
            foreach ($result as $item) {
                if ($item->words_tags != null) {
                    // words_tags['values']
                    $result[$i]->words_tags = json_decode($item->words_tags, true);
                    // words_tags['array']
                    $result[$i] = $this->makeWordsTagsCol($result[$i]);
                }
                $i++;
            }
        } else {
            $result = $this->makeWordsTagsCol($result);
        }

        return $result;
    }

    public function makeWordsTagsCol($item)
    {
        if (isset($item->words_tags['values']) && count($item->words_tags['values']) > 0) {
            $item->words_tags['array'] = [];
            foreach ($item->words_tags['values'] as $row) {
                if (is_object($row) && get_class($row) === 'stdClass') {
                    $row = get_object_vars($row);
                }
                array_push($item->words_tags['array'], $row['ts_id']);
            }
        } else {
            $item->words_tags['array'] = [];
        }
        return $item;
    }
}

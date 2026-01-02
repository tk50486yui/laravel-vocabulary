<?php
namespace App\Services\Outputs;

/**
 *     輸出資料處理
 **/

class ArticlesOutput
{
    public function genArticlesTags($result, $multiple = true)
    {
        if ($multiple) {
            $i = 0;
            foreach ($result as $item) {
                if ($item->articles_tags != null) {
                    // articles_tags['values']
                    $result[$i]->articles_tags = json_decode($item->articles_tags, true);
                    // articles_tags['array']
                    $result[$i] = $this->makeArticlesTagsCol($result[$i]);
                }
                $i++;
            }
        } else {
            $result = $this->makeArticlesTagsCol($result);
        }

        return $result;
    }

    public function makeArticlesTagsCol($item)
    {
        if (isset($item->articles_tags['values']) && count($item->articles_tags['values']) > 0) {
            $item->articles_tags['array'] = [];
            foreach ($item->articles_tags['values'] as $row) {
                if (is_object($row) && get_class($row) === 'stdClass') {
                    $row = get_object_vars($row);
                }
                array_push($item->articles_tags['array'], $row['ts_id']);
            }
        } else {
            $item->articles_tags['array'] = [];
        }
        return $item;
    }
}

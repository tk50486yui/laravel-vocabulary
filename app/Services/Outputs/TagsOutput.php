<?php
namespace App\Services\Outputs;

/**
 *     輸出資料處理
 **/

class TagsOutput
{
    // Tree
    function buildTagsTree($tags, $parent_id = null, $parents = [])
    {

        $tree = [];

        foreach ($tags as $tag) {
            if ($tag['ts_parent_id'] == $parent_id) {
                $node = [
                    'id'             => $tag['id'],
                    'ts_name'        => $tag['ts_name'],
                    'ts_parent_id'   => $tag['ts_parent_id'],
                    'ts_level'       => $tag['ts_level'],
                    'ts_order'       => $tag['ts_order'],
                    'tc_id'          => $tag['tc_id'],
                    'tc_background'  => $tag['tc_background'],
                    'tc_color'       => $tag['tc_color'],
                    'tc_border'      => $tag['tc_border'],
                    'ts_parent_name' => $tag['ts_parent_name'],
                    'parents'        => $parents,
                    'children'       => $this->buildTagsTree($tags, $tag['id'], array_merge($parents, [$tag['id']])),
                ];

                $tree[] = $node;
            }
        }

        return $tree;
    }
}

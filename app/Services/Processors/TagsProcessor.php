<?php
namespace App\Services\Processors;

/**
 *  幫助 Service 處理較複雜的客製資料
 **/

class TagsProcessor
{
    public function populate($data)
    {
        $data['ts_name']      = $data['ts_name'] ?? null;
        $data['ts_parent_id'] = $data['ts_parent_id'] ?? null;
        $data['tc_id']        = $data['tc_id'] ?? null;

        return $data;
    }

    public function setLevel($TagsRepo, $reqData)
    {
        if (isset($reqData['ts_parent_id']) && $reqData['ts_parent_id'] != null) {
            $parent = $TagsRepo->find($reqData['ts_parent_id']);
            return $parent->ts_level + 1;
        } else {
            return 1;
        }
    }

    public function setOrder($TagsRepo, $reqData, $id = null)
    {
        if (isset($reqData['ts_parent_id']) && $reqData['ts_parent_id'] != null) {
            if ($id) { // update
                $result = $TagsRepo->findParentExistByID($id, $reqData['ts_parent_id']);
                // 若 ts_parent_id 沒變動 則接受 request 的值
                if ($result->is_parent_change === true) {
                    return $reqData['ts_order'];
                }
            }
            // add 或 ts_parent_id 若變動
            $children = $TagsRepo->findMaxOrderByParent($reqData['ts_parent_id']);
            if ($children->sibling_count == 0) {
                return 0;
            } else {
                return $children->max_ts_order + 1;
            }
        } else {
            if ($id) { // update
                $single = $TagsRepo->find($id);
                if ($single->ts_parent_id == null || $single->ts_parent_id == '') {
                    return $single->ts_order;
                }
            }
            $sibling = $TagsRepo->findOrderInFirstLevel();
            if ($sibling && $sibling != null) {
                return $sibling->max_ts_order + 1;
            } else {
                return 0;
            }
        }
    }
}

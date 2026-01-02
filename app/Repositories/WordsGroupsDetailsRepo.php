<?php
namespace App\Repositories;

use App\Models\WordsGroupsDetails;
use Illuminate\Support\Facades\DB;

class WordsGroupsDetailsRepo
{
    public function findByWgID($wg_id)
    {
        $query = "SELECT
                    ws.ws_name as ws_name, wgd.*
                FROM
                    words_groups_details wgd
                LEFT JOIN
                    words ws ON wgd.ws_id =  ws.id
                WHERE
                    wgd.wg_id = ?
                ORDER BY
                    ws.created_at DESC";

        return DB::select($query, [$wg_id]);
    }

    public function findByAssociatedIDs($ws_id, $wg_id)
    {
        return WordsGroupsDetails::where('ws_id', $ws_id)
            ->where('wg_id', $wg_id)
            ->first();
    }

    public function add($data)
    {
        WordsGroupsDetails::create([
            'ws_id' => $data['ws_id'],
            'wg_id' => $data['wg_id'],
        ]);
    }

    public function deleteByWgID($wg_id)
    {
        WordsGroupsDetails::where('wg_id', $wg_id)->delete();
    }
}

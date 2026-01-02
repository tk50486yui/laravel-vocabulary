<?php
namespace App\Repositories;

use App\Models\WordsTags;
use Illuminate\Support\Facades\DB;

class WordsTagsRepo
{
    public function findByWordsID($ws_id)
    {
        $query = "SELECT
                    ts.id as ts_id, ts.ts_name
                FROM
                    words_tags wt
                LEFT JOIN words ws ON wt.ws_id = ws.id
                LEFT JOIN tags ts ON wt.ts_id =  ts.id
                WHERE
                    wt.ws_id = ?";

        return DB::select($query, [$ws_id]);
    }

    public function findByAssociatedIDs($ws_id, $ts_id)
    {
        return WordsTags::where('ws_id', $ws_id)
            ->where('ts_id', $ts_id)
            ->first();
    }

    public function add($data)
    {
        return WordsTags::create([
            'ws_id' => $data['ws_id'],
            'ts_id' => $data['ts_id'],
        ]);
    }

    public function deleteByWsID($ws_id)
    {
        WordsTags::where('ws_id', $ws_id)->delete();
    }

}

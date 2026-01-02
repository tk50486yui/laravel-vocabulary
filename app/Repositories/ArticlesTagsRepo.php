<?php
namespace App\Repositories;

use App\Models\ArticlesTags;
use Illuminate\Support\Facades\DB;

class ArticlesTagsRepo
{
    public function findByArtiID($arti_id)
    {
        $query = "SELECT
                    ts.id as ts_id, ts.ts_name
                FROM
                    articles_tags ats
                LEFT JOIN articles arti ON ats.arti_id = arti.id
                LEFT JOIN tags ts ON ats.ts_id =  ts.id
                WHERE
                    ats.arti_id = ?";

        return DB::select($query, [$arti_id]);
    }

    public function findByAssociatedIDs($arti_id, $ts_id)
    {
        return ArticlesTags::where('arti_id', $arti_id)
            ->where('ts_id', $ts_id)
            ->first();
    }

    public function add($data)
    {
        return ArticlesTags::create([
            'arti_id' => $data['arti_id'],
            'ts_id'   => $data['ts_id'],
        ]);
    }

    public function deleteByArtiID($arti_id)
    {
        ArticlesTags::where('arti_id', $arti_id)->delete();
    }
}

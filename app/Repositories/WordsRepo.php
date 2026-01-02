<?php
namespace App\Repositories;

use App\Models\Words;
use Illuminate\Support\Facades\DB;

class WordsRepo
{
    public function find($id)
    {
        $query = "SELECT
                    ws.*, cate.cate_name as cate_name
                FROM
                    words ws
                LEFT JOIN
                    categories cate ON ws.cate_id =  cate.id
                WHERE
                    ws.id = ?";

        return DB::selectOne($query, [$id]);
    }

    public function findAll()
    {
        $query = "SELECT
                    ws.*, cate.cate_name as cate_name,
                    json_build_object('values',
                        (
                            SELECT
                                json_agg(json_build_object(
                                            'ts_id', ts.id,
                                            'ts_name', ts.ts_name,
                                            'tc_color', COALESCE(tc.tc_color, '#fff'),
                                            'tc_background', COALESCE(tc.tc_background, '#000'),
                                            'tc_border', COALESCE(tc.tc_border, 'rgb(244, 202, 202)')
                                        )
                                    ORDER BY ts.ts_order ASC
                                )
                            FROM
                                words_tags wt
                            LEFT JOIN
                                tags ts ON wt.ts_id = ts.id
                            LEFT JOIN
                                tags_color tc ON ts.tc_id = tc.id
                            WHERE
                                wt.ws_id = ws.id
                        )
                    ) AS words_tags
                FROM
                    words ws
                LEFT JOIN
                    categories cate ON ws.cate_id =  cate.id
                ORDER BY
                    ws.id DESC";

        return DB::select($query);
    }

    public function findByName($ws_name)
    {
        return Words::where('ws_name', $ws_name)->first();
    }

    public function store($data)
    {
        $new = Words::create([
            'ws_name'          => $data['ws_name'],
            'ws_definition'    => $data['ws_definition'],
            'ws_pronunciation' => $data['ws_pronunciation'],
            'ws_slogan'        => $data['ws_slogan'],
            'ws_description'   => $data['ws_description'],
            'ws_forget_count'  => $data['ws_forget_count'],
            'ws_order'         => $data['ws_order'],
            'cate_id'          => $data['cate_id'],
        ]);

        return $new->id;
    }

    public function update($data, $id)
    {
        $word = Words::find($id);
        $word->update([
            'ws_name'          => $data['ws_name'],
            'ws_definition'    => $data['ws_definition'],
            'ws_pronunciation' => $data['ws_pronunciation'],
            'ws_slogan'        => $data['ws_slogan'],
            'ws_description'   => $data['ws_description'],
            'ws_forget_count'  => $data['ws_forget_count'],
            'ws_order'         => $data['ws_order'],
            'cate_id'          => $data['cate_id'],
        ]);
    }

    public function updateCommon($data, $id)
    {
        $word = Words::find($id);
        $word->update([
            'ws_is_common' => $data['ws_is_common'],
        ]);
    }

    public function updateImportant($data, $id)
    {
        $word = Words::find($id);
        $word->update([
            'ws_is_important' => $data['ws_is_important'],
        ]);
    }

    public function updateNullByCateID($cate_id)
    {
        Words::where('cate_id', $cate_id)->update(['cate_id' => null]);
    }

    public function deleteByID($id)
    {
        Words::where('id', $id)->delete();
    }

}

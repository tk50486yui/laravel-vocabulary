<?php
namespace App\Repositories;

use App\Models\Articles;
use Illuminate\Support\Facades\DB;

class ArticlesRepo
{
    public function find($id)
    {
        $query = "SELECT
                    arti.*, cate.cate_name as cate_name
                FROM
                    articles arti
                LEFT JOIN
                    categories cate ON arti.cate_id = cate.id
                WHERE
                    arti.id = ?";

        return DB::selectOne($query, [$id]);
    }

    public function findAll()
    {
        $query = "SELECT
                    arti.*, cate.cate_name as cate_name,
                    TO_CHAR(arti.created_at, 'YYYY-MM-DD HH24:MI:SS') AS created_at,
                    TO_CHAR(arti.updated_at, 'YYYY-MM-DD HH24:MI:SS') AS updated_at,
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
                                )
                            FROM
                                articles_tags ats
                            LEFT JOIN
                                tags ts ON ats.ts_id = ts.id
                            LEFT JOIN
                                tags_color tc ON ts.tc_id = tc.id
                            WHERE
                                ats.arti_id = arti.id

                        )
                    ) AS articles_tags
                FROM
                    articles arti
                LEFT JOIN
                    categories cate ON arti.cate_id = cate.id
                ORDER BY
                    arti.id DESC";

        return DB::select($query);
    }

    public function add($data)
    {
        $new = Articles::create([
            'arti_title'   => $data['arti_title'],
            'arti_content' => $data['arti_content'],
            'arti_order'   => $data['arti_order'],
            'cate_id'      => $data['cate_id'],
        ]);

        return $new->id;
    }

    public function edit($data, $id)
    {
        $articles = Articles::find($id);
        $articles->update([
            'arti_title'   => $data['arti_title'],
            'arti_content' => $data['arti_content'],
            'arti_order'   => $data['arti_order'],
            'cate_id'      => $data['cate_id'],
        ]);
    }

    public function updateNullByCateID($cate_id)
    {
        Articles::where('cate_id', $cate_id)->update(['cate_id' => null]);
    }

    public function deleteByID($id)
    {
        Articles::where('id', $id)->delete();
    }
}

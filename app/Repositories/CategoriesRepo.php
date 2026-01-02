<?php
namespace App\Repositories;

use App\Models\Categories;
use Illuminate\Support\Facades\DB;

class CategoriesRepo
{
    public function find($id)
    {
        return Categories::where('id', $id)->first();
    }

    public function findAll()
    {
        $query = "SELECT
                    cate.*,
                    parent.cate_name AS cate_parent_name
                FROM
                    categories cate
                LEFT JOIN
                    categories AS parent ON cate.cate_parent_id = parent.id
                ORDER BY
                    cate.cate_order ASC";

        return DB::select($query);
    }

    public function findRecent()
    {
        $query = "SELECT
                cate.*,
                parent.cate_name AS cate_parent_name
            FROM
                categories cate
            LEFT JOIN
                categories AS parent ON cate.cate_parent_id = parent.id
            ORDER BY
                cate.created_at DESC, cate.updated_at DESC";

        return DB::select($query);
    }

    public function findByName($cate_name)
    {
        return Categories::where('cate_name', $cate_name)->first();
    }

    public function add($data)
    {
        $new = Categories::create([
            'cate_name'      => $data['cate_name'],
            'cate_parent_id' => $data['cate_parent_id'],
            'cate_level'     => $data['cate_level'],
            'cate_order'     => $data['cate_order'],
        ]);

        return $new->id;
    }

    public function edit($data, $id)
    {
        $categories = Categories::find($id);
        $categories->update([
            'cate_name'      => $data['cate_name'],
            'cate_parent_id' => $data['cate_parent_id'],
            'cate_level'     => $data['cate_level'],
            'cate_order'     => $data['cate_order'],
        ]);
    }

    public function editOrder($cate_order, $id)
    {
        $categories = Categories::find($id);
        $categories->update([
            'cate_order' => $cate_order,
        ]);
    }

    public function findParentExistByID($id, $cate_parent_id)
    {
        $bindings = [
            'id'             => $id,
            'cate_parent_id' => $cate_parent_id,
        ];
        $query = "SELECT EXISTS (
                    SELECT 1
                    FROM
                        categories
                    WHERE
                        id = :id AND (
                        cate_parent_id = :cate_parent_id OR
                            (cate_parent_id IS NULL AND :cate_parent_id IS NULL)
                        )
                ) AS is_parent_change";

        return DB::selectOne($query, $bindings);
    }

    public function findChildren($id)
    {
        $query = "SELECT * FROM categories
                WHERE
                    cate_parent_id = ?
                ORDER BY
                    cate_order ASC";

        return DB::select($query, [$id]);
    }

    public function findCheckParent($id, $cate_parent_id)
    {
        $param = ['cate_parent_id' => $cate_parent_id, 'id' => $id];
        $query = "SELECT * FROM categories
                WHERE
                    cate_parent_id = :id
                AND
                    id = :cate_parent_id";

        return DB::select($query, $param);

    }

    public function findMaxOrderByParent($cate_parent_id)
    {
        $query = "SELECT
                    MAX(cate_order) as max_cate_order,
                    COUNT(id) as sibling_count
                FROM
                    categories
                WHERE
                    cate_parent_id = ?";

        $result = DB::selectOne($query, [$cate_parent_id]);

        return $result;
    }

    public function findOrderInFirstLevel()
    {
        $query = "SELECT
                    MAX(cate_order) as max_cate_order
                FROM
                    categories
                WHERE
                    cate_parent_id IS NULL";

        $result = DB::selectOne($query);

        return $result;
    }

    public function deleteByID($id)
    {
        Categories::where('id', $id)->delete();
    }
}

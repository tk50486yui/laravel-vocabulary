<?php
namespace App\Repositories;

use App\Models\TagsColor;

class TagsColorRepo
{
    public function find($id)
    {
        return TagsColor::where('id', $id)->first();
    }

    public function findAll()
    {
        return TagsColor::orderBy('created_at', 'ASC')->get();
    }

    public function add($data)
    {
        $new = TagsColor::create([
            'tc_color'      => $data['tc_color'],
            'tc_background' => $data['tc_background'],
            'tc_border'     => $data['tc_border'],
        ]);

        return $new->id;
    }

    public function edit($data, $id)
    {
        $tags = TagsColor::find($id);
        $tags->update([
            'tc_color'      => $data['tc_color'],
            'tc_background' => $data['tc_background'],
            'tc_border'     => $data['tc_border'],
        ]);
    }

    public function deleteByID($id)
    {
        TagsColor::where('id', $id)->delete();
    }
}

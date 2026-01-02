<?php
namespace App\Repositories;

use App\Models\WordsGroups;

class WordsGroupsRepo
{
    public function find($id)
    {
        return WordsGroups::where('id', $id)->first();
    }

    public function findAll()
    {
        return WordsGroups::orderBy('created_at', 'DESC')->get();
    }

    public function findByName($wg_name)
    {
        return WordsGroups::where('wg_name', $wg_name)->first();
    }

    public function add($data)
    {
        $new = WordsGroups::create([
            'wg_name' => $data['wg_name'],
        ]);

        return $new->id;
    }

    public function edit($data, $id)
    {
        $wordGroups = WordsGroups::find($id);
        $wordGroups->update([
            'wg_name' => $data['wg_name'],
        ]);
    }

    public function deleteByID($id)
    {
        WordsGroups::where('id', $id)->delete();
    }
}

<?php
namespace App\Services;

use App\Repositories\TagsColorRepo;
use App\Repositories\TagsRepo;
use App\Validators\TagsColorValidator;
use Illuminate\Support\Facades\DB;

class TagsColorService
{
    public function find($id)
    {
        $TagsColorRepo = new TagsColorRepo();
        return $TagsColorRepo->find($id);
    }

    public function findAll()
    {
        $TagsColorRepo = new TagsColorRepo();
        return $TagsColorRepo->findAll();
    }

    public function add($reqData)
    {
        DB::transaction(function () use ($reqData) {
            $TagsColorValidator = new TagsColorValidator();
            $TagsColorRepo      = new TagsColorRepo();
            $TagsColorValidator->validate($reqData, null);
            $TagsColorRepo->add($reqData);
        });

    }

    public function edit($reqData, $id)
    {
        DB::transaction(function () use ($reqData, $id) {
            $TagsColorValidator = new TagsColorValidator();
            $TagsColorRepo      = new TagsColorRepo();
            $TagsColorValidator->validate($reqData, $id);
            $TagsColorRepo->edit($reqData, $id);
        });
    }

    public function deleteByID($id)
    {
        DB::transaction(function () use ($id) {
            $TagsColorValidator = new TagsColorValidator();
            $TagsColorRepo      = new TagsColorRepo();
            $TagsRepo           = new TagsRepo();
            $TagsColorValidator->validate([], $id, false);
            $TagsRepo->updateNullByTcID($id); // tags tc_id
            $TagsColorRepo->deleteByID($id);
        });
    }
}

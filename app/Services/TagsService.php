<?php
namespace App\Services;

use App\Repositories\TagsRepo;
use App\Services\Outputs\TagsOutput;
use App\Services\Processors\TagsProcessor;
use App\Validators\TagsValidator;
use Illuminate\Support\Facades\DB;

class TagsService
{
    public function find($id)
    {
        $TagsRepo = new TagsRepo();
        return $TagsRepo->find($id);
    }

    public function findAll()
    {
        $TagsRepo   = new TagsRepo();
        $TagsOutput = new TagsOutput();
        $result     = $TagsRepo->findAll();
        $result     = array_map('get_object_vars', $result);
        return $TagsOutput->buildTagsTree($result);
    }

    public function findRecent()
    {
        $TagsRepo = new TagsRepo();
        $result   = $TagsRepo->findRecent();
        $result   = array_map('get_object_vars', $result);
        $i        = 0;
        foreach ($result as $item) {
            $result[$i]['children'] = $TagsRepo->findChildren($item['id']);
            $i++;
        }
        return $result;
    }

    public function add($reqData)
    {
        DB::transaction(function () use ($reqData) {
            $TagsValidator = new TagsValidator();
            $TagsProcessor = new TagsProcessor();
            $TagsRepo      = new TagsRepo();
            $TagsValidator->validate($reqData, null);
            $reqData             = $TagsProcessor->populate($reqData);
            $reqData['ts_level'] = $TagsProcessor->setLevel($TagsRepo, $reqData);
            $reqData['ts_order'] = $TagsProcessor->setOrder($TagsRepo, $reqData);
            $TagsRepo->add($reqData);
        });

    }

    public function edit($reqData, $id)
    {
        DB::transaction(function () use ($reqData, $id) {
            $TagsValidator = new TagsValidator();
            $TagsProcessor = new TagsProcessor();
            $TagsRepo      = new TagsRepo();
            $reqData       = $TagsProcessor->populate($reqData);
            $TagsValidator->validate($reqData, $id);
            $reqData['ts_level'] = $TagsProcessor->setLevel($TagsRepo, $reqData);
            $reqData['ts_order'] = $TagsProcessor->setOrder($TagsRepo, $reqData, $id);
            $TagsRepo->edit($reqData, $id);
        });
    }

    public function editOrder($reqData)
    {
        DB::transaction(function () use ($reqData) {
            $TagsValidator = new TagsValidator();
            $TagsRepo      = new TagsRepo();
            foreach ($reqData as $item) {
                $TagsValidator->validate($item, $item['id'], false);
                $TagsRepo->editOrder($item['ts_order'], $item['id']);
            }
        });
    }

    public function deleteByID($id)
    {
        DB::transaction(function () use ($id) {
            $TagsValidator = new TagsValidator();
            $TagsProcessor = new TagsProcessor();
            $TagsRepo      = new TagsRepo();
            $TagsValidator->validate([], $id, false);
            $children = $TagsRepo->findChildren($id);
            $TagsRepo->deleteByID($id);
            foreach ($children as $item) {
                $new                 = [];
                $new['ts_parent_id'] = null;
                $new['ts_order']     = $TagsProcessor->setOrder($TagsRepo, $new);
                $TagsRepo->editOrder($new['ts_order'], $item->id);
            }
        });
    }
}

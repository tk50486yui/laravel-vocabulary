<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Services\Processors\TagsProcessor;
use App\Services\Outputs\TagsOutput;
use App\Observers\TagsObserver;
use App\Repositories\TagsRepo;

class TagsService
{
    public function find($id)
    {
        $TagsRepo = new TagsRepo();
        return $TagsRepo->find($id);
    }

    public function findAll()
    {
        $TagsRepo = new TagsRepo();
        $TagsOutput = new TagsOutput();
        $result = $TagsRepo->findAll();
        $result = array_map('get_object_vars', $result);
        return $TagsOutput->buildTagsTree($result);
    }

    public function findRecent()
    {     
        $TagsRepo = new TagsRepo();
        $result = $TagsRepo->findRecent();
        $result = array_map('get_object_vars', $result);
        $i = 0;
        foreach($result as $item){
            $result[$i]['children'] = $TagsRepo->findChildren($item['id']);
            $i++;
        }
        return $result;
    }  

    public function add($reqData)
    {
        DB::transaction(function () use ($reqData){
            $TagsObserver = new TagsObserver();
            $TagsProcessor = new TagsProcessor();
            $TagsRepo = new TagsRepo();
            $TagsObserver->validate($reqData, null);
            $reqData = $TagsProcessor->populate($reqData);
            $reqData['ts_level'] = $TagsProcessor->setLevel($TagsRepo, $reqData);
            $reqData['ts_order'] = $TagsProcessor->setOrder($TagsRepo, $reqData);
            $TagsRepo->add($reqData);
        });
       
    }

    public function edit($reqData, $id)
    {
        DB::transaction(function () use ($reqData, $id){
            $TagsObserver = new TagsObserver();
            $TagsProcessor = new TagsProcessor();
            $TagsRepo = new TagsRepo();
            $reqData = $TagsProcessor->populate($reqData);
            $TagsObserver->validate($reqData, $id);
            $reqData['ts_level'] = $TagsProcessor->setLevel($TagsRepo, $reqData);
            $reqData['ts_order'] = $TagsProcessor->setOrder($TagsRepo, $reqData, $id);
            $TagsRepo->edit($reqData, $id);
        });
    }

    public function editOrder($reqData)
    {
        DB::transaction(function () use ($reqData){
            $TagsObserver = new TagsObserver();
            $TagsRepo = new TagsRepo();
            foreach($reqData as $item){
                $TagsObserver->validate($item, $item['id'], false);
                $TagsRepo->editOrder($item['ts_order'], $item['id']);
            }
        });
    }

    public function deleteByID($id)
    {     
        DB::transaction(function () use ($id){
            $TagsObserver = new TagsObserver();
            $TagsProcessor = new TagsProcessor();
            $TagsRepo = new TagsRepo();
            $TagsObserver->validate(array(), $id, false);
            $children = $TagsRepo->findChildren($id);
            $TagsRepo->deleteByID($id);
            foreach($children as $item){
                $new = array();
                $new['ts_parent_id'] = null;
                $new['ts_order'] = $TagsProcessor->setOrder($TagsRepo, $new);
                $TagsRepo->editOrder($new['ts_order'], $item->id);
            }
        });
    }
}
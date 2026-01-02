<?php
namespace App\Services;

use App\Repositories\WordsGroupsDetailsRepo;
use App\Repositories\WordsGroupsRepo;
use App\Services\Processors\WordsGroupsProcessor;
use App\Validators\WordsGroupsDetailsValidator;
use App\Validators\WordsGroupsValidator;
use Illuminate\Support\Facades\DB;

class WordsGroupsService
{
    public function find($id)
    {
        $WordsGroupsDetailsRepo = new WordsGroupsDetailsRepo();
        return $WordsGroupsDetailsRepo->findByWgID($id);
    }

    public function findAll()
    {
        $WordsGroupsRepo        = new WordsGroupsRepo();
        $WordsGroupsDetailsRepo = new WordsGroupsDetailsRepo();
        $result                 = $WordsGroupsRepo->findAll();
        $i                      = 0;
        if (count($result) > 0) {
            foreach ($result as $item) {
                $result[$i]['details'] = $WordsGroupsDetailsRepo->findByWgID($item->id);
                $i++;
            }
        }

        return $result;
    }

    public function add($reqData)
    {
        DB::transaction(function () use ($reqData) {
            $WordsGroupsProcessor        = new WordsGroupsProcessor();
            $WordsGroupsValidator        = new WordsGroupsValidator();
            $WordsGroupsDetailsValidator = new WordsGroupsDetailsValidator();
            $WordsGroupsRepo             = new WordsGroupsRepo();
            $WordsGroupsDetailsRepo      = new WordsGroupsDetailsRepo();
            $WordsGroupsValidator->validate($reqData, null);
            $wgd_array = $WordsGroupsProcessor->begin($reqData);
            $id        = $WordsGroupsRepo->add($reqData);
            if ($wgd_array) {
                foreach ($wgd_array as $item) {
                    $new          = [];
                    $new['wg_id'] = $id;
                    $new['ws_id'] = $item;
                    $WordsGroupsDetailsValidator->validate($new, null);
                    $WordsGroupsDetailsRepo->add($new);
                }
            }
        });
    }

    public function edit($reqData, $id)
    {
        DB::transaction(function () use ($reqData, $id) {
            $WordsGroupsProcessor        = new WordsGroupsProcessor();
            $WordsGroupsValidator        = new WordsGroupsValidator();
            $WordsGroupsDetailsValidator = new WordsGroupsDetailsValidator();
            $WordsGroupsRepo             = new WordsGroupsRepo();
            $WordsGroupsDetailsRepo      = new WordsGroupsDetailsRepo();
            $WordsGroupsValidator->validate($reqData, $id);
            $wgd_array = $WordsGroupsProcessor->begin($reqData);
            $WordsGroupsRepo->edit($reqData, $id);
            if ($wgd_array) {
                $WordsGroupsDetailsRepo->deleteByWgID($id);
                foreach ($wgd_array as $item) {
                    $new          = [];
                    $new['wg_id'] = $id;
                    $new['ws_id'] = $item;
                    $WordsGroupsDetailsValidator->validate($new, null);
                    $WordsGroupsDetailsRepo->add($new);
                }
            } else {
                $WordsGroupsDetailsRepo->deleteByWgID($id);
            }
        });
    }

    public function deleteByID($id)
    {
        DB::transaction(function () use ($id) {
            $WordsGroupsValidator = new WordsGroupsValidator();
            $WordsGroupsRepo      = new WordsGroupsRepo();
            $WordsGroupsValidator->validate([], $id, false);
            $WordsGroupsRepo->deleteByID($id);
        });
    }
}

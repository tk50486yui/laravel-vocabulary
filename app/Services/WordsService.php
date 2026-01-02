<?php
namespace App\Services;

use App\Repositories\WordsRepo;
use App\Repositories\WordsTagsRepo;
use App\Services\Outputs\WordsOutput;
use App\Services\Processors\WordsProcessor;
use App\Validators\WordsTagsValidator;
use App\Validators\WordsValidator;
use Illuminate\Support\Facades\DB;

class WordsService
{
    public function find($id)
    {
        $WordsRepo     = new WordsRepo();
        $WordsTagsRepo = new WordsTagsRepo();
        $WordsOutput   = new WordsOutput();
        $result        = $WordsRepo->find($id);
        if ($result) {
            $result->words_tags['values'] = $WordsTagsRepo->findByWordsID($id);
            $result                       = $WordsOutput->genWordsTags($result, false);
        }
        return $result;
    }

    public function findByName($ws_name)
    {
        $WordsRepo = new WordsRepo();
        $result    = $WordsRepo->findByName($ws_name);
        return $result;
    }

    public function findAll()
    {
        $WordsRepo   = new WordsRepo();
        $WordsOutput = new WordsOutput();
        $result      = $WordsRepo->findAll();
        return $WordsOutput->genWordsTags($result, true);
    }

    public function store($reqData)
    {
        DB::transaction(function () use ($reqData) {
            $WordsProcessor     = new WordsProcessor();
            $WordsValidator     = new WordsValidator();
            $WordsTagsValidator = new WordsTagsValidator();
            $WordsRepo          = new WordsRepo();
            $WordsTagsRepo      = new WordsTagsRepo();
            $reqData            = $WordsProcessor->populate($reqData);
            $WordsValidator->validate($reqData, null);
            $array_ts_id = $WordsProcessor->begin($reqData);
            $id          = $WordsRepo->store($reqData);
            if ($array_ts_id) {
                foreach ($array_ts_id as $item) {
                    $new          = [];
                    $new['ws_id'] = $id;
                    $new['ts_id'] = $item;
                    $WordsTagsValidator->validate($new, null);
                    $WordsTagsRepo->add($new);
                }
            }
        });

    }

    public function update($reqData, $id)
    {
        DB::transaction(function () use ($reqData, $id) {
            $WordsProcessor     = new WordsProcessor();
            $WordsValidator     = new WordsValidator();
            $WordsTagsValidator = new WordsTagsValidator();
            $WordsRepo          = new WordsRepo();
            $WordsTagsRepo      = new WordsTagsRepo();
            $reqData            = $WordsProcessor->populate($reqData);
            $WordsValidator->validate($reqData, $id);
            $array_ts_id = $WordsProcessor->begin($reqData);
            $WordsRepo->update($reqData, $id);
            if ($array_ts_id) {
                $WordsTagsRepo->deleteByWsID($id);
                foreach ($array_ts_id as $item) {
                    $new          = [];
                    $new['ws_id'] = $id;
                    $new['ts_id'] = $item;
                    $WordsTagsValidator->validate($new, null);
                    $WordsTagsRepo->add($new);
                }
            } else {
                $WordsTagsRepo->deleteByWsID($id);
            }
        });

    }

    public function updateCommon($reqData, $id)
    {
        DB::transaction(function () use ($reqData, $id) {
            $WordsValidator = new WordsValidator();
            $WordsRepo      = new WordsRepo();
            $WordsValidator->validate($reqData, $id, false);
            $WordsRepo->updateCommon($reqData, $id);
        });
    }

    public function updateImportant($reqData, $id)
    {
        DB::transaction(function () use ($reqData, $id) {
            $WordsValidator = new WordsValidator();
            $WordsRepo      = new WordsRepo();
            $WordsValidator->validate($reqData, $id, false);
            $WordsRepo->updateImportant($reqData, $id);
        });

    }

    public function deleteByID($id)
    {
        DB::transaction(function () use ($id) {
            $WordsValidator = new WordsValidator();
            $WordsRepo      = new WordsRepo();
            $WordsValidator->validate([], $id, false);
            $WordsRepo->deleteByID($id);
        });
    }
}

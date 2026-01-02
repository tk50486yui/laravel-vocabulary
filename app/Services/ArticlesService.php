<?php
namespace App\Services;

use App\Repositories\ArticlesRepo;
use App\Repositories\ArticlesTagsRepo;
use App\Services\Outputs\ArticlesOutput;
use App\Services\Processors\ArticlesProcessor;
use App\Validators\ArticlesTagsValidator;
use App\Validators\ArticlesValidator;
use Illuminate\Support\Facades\DB;

class ArticlesService
{
    public function find($id)
    {
        $ArticlesRepo     = new ArticlesRepo();
        $ArticlesTagsRepo = new ArticlesTagsRepo();
        $ArticlesOutput   = new ArticlesOutput();
        $result           = $ArticlesRepo->find($id);
        if ($result) {
            $result->articles_tags['values'] = $ArticlesTagsRepo->findByArtiID($id);
            $result                          = $ArticlesOutput->genArticlesTags($result, false);
        }

        return $result;
    }

    public function findAll()
    {
        $ArticlesRepo   = new ArticlesRepo();
        $result         = $ArticlesRepo->findAll();
        $ArticlesOutput = new ArticlesOutput();
        return $ArticlesOutput->genArticlesTags($result, true);
    }
    public function add($reqData)
    {
        DB::transaction(function () use ($reqData) {
            $ArticlesProcessor     = new ArticlesProcessor();
            $ArticlesValidator     = new ArticlesValidator();
            $ArticlesTagsValidator = new ArticlesTagsValidator();
            $ArticlesRepo          = new ArticlesRepo();
            $ArticlesTagsRepo      = new ArticlesTagsRepo();
            $reqData               = $ArticlesProcessor->populate($reqData);
            $ArticlesValidator->validate($reqData, null);
            $array_ts_id = $ArticlesProcessor->begin($reqData);
            $id          = $ArticlesRepo->add($reqData);
            if ($array_ts_id) {
                foreach ($array_ts_id as $item) {
                    $new            = [];
                    $new['arti_id'] = $id;
                    $new['ts_id']   = $item;
                    $ArticlesTagsValidator->validate($new, null);
                    $ArticlesTagsRepo->add($new);
                }
            }
        });
    }

    public function edit($reqData, $id)
    {
        DB::transaction(function () use ($reqData, $id) {
            $ArticlesProcessor     = new ArticlesProcessor();
            $ArticlesValidator     = new ArticlesValidator();
            $ArticlesTagsValidator = new ArticlesTagsValidator();
            $ArticlesRepo          = new ArticlesRepo();
            $ArticlesTagsRepo      = new ArticlesTagsRepo();
            $ArticlesValidator->validate($reqData, $id);
            $array_ts_id = $ArticlesProcessor->begin($reqData);
            $ArticlesRepo->edit($reqData, $id);
            if ($array_ts_id) {
                $ArticlesTagsRepo->deleteByArtiID($id);
                foreach ($array_ts_id as $item) {
                    $new            = [];
                    $new['arti_id'] = $id;
                    $new['ts_id']   = $item;
                    $ArticlesTagsValidator->validate($new, null);
                    $ArticlesTagsRepo->add($new);
                }
            } else {
                $ArticlesTagsRepo->deleteByArtiID($id);
            }
        });
    }

    public function deleteByID($id)
    {
        DB::transaction(function () use ($id) {
            $ArticlesValidator = new ArticlesValidator();
            $ArticlesRepo      = new ArticlesRepo();
            $ArticlesValidator->validate([], $id, false);
            $ArticlesRepo->deleteByID($id);
        });
    }
}

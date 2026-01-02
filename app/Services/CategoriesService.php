<?php
namespace App\Services;

use App\Repositories\ArticlesRepo;
use App\Repositories\CategoriesRepo;
use App\Repositories\WordsRepo;
use App\Services\Outputs\CategoriesOutput;
use App\Services\Processors\CategoriesProcessor;
use App\Validators\CategoriesValidator;
use Illuminate\Support\Facades\DB;

class CategoriesService
{
    public function find($id)
    {
        $CategoriesRepo = new CategoriesRepo();
        return $CategoriesRepo->find($id);
    }

    public function findAll()
    {
        $CategoriesRepo   = new CategoriesRepo();
        $CategoriesOutput = new CategoriesOutput();
        $result           = $CategoriesRepo->findAll();
        $result           = array_map('get_object_vars', $result);
        return $CategoriesOutput->buildCategoriesTree($result);
    }

    public function findRecent()
    {
        $CategoriesRepo = new CategoriesRepo();
        $result         = $CategoriesRepo->findRecent();
        $result         = array_map('get_object_vars', $result);
        $i              = 0;
        foreach ($result as $item) {
            $result[$i]['children'] = $CategoriesRepo->findChildren($item['id']);
            $i++;
        }
        return $result;
    }

    public function add($reqData)
    {
        DB::transaction(function () use ($reqData) {
            $CategoriesValidator = new CategoriesValidator();
            $CategoriesProcessor = new CategoriesProcessor();
            $CategoriesRepo      = new CategoriesRepo();
            $CategoriesValidator->validate($reqData, null);
            $reqData               = $CategoriesProcessor->populate($reqData);
            $reqData['cate_level'] = $CategoriesProcessor->setLevel($CategoriesRepo, $reqData);
            $reqData['cate_order'] = $CategoriesProcessor->setOrder($CategoriesRepo, $reqData);
            $CategoriesRepo->add($reqData);
        });
    }

    public function edit($reqData, $id)
    {
        DB::transaction(function () use ($reqData, $id) {
            $CategoriesValidator = new CategoriesValidator();
            $CategoriesProcessor = new CategoriesProcessor();
            $CategoriesRepo      = new CategoriesRepo();
            $reqData             = $CategoriesProcessor->populate($reqData);
            $CategoriesValidator->validate($reqData, $id);
            $reqData['cate_level'] = $CategoriesProcessor->setLevel($CategoriesRepo, $reqData);
            $reqData['cate_order'] = $CategoriesProcessor->setOrder($CategoriesRepo, $reqData, $id);
            $CategoriesRepo->edit($reqData, $id);
        });
    }

    public function editOrder($reqData)
    {
        DB::transaction(function () use ($reqData) {
            $CategoriesValidator = new CategoriesValidator();
            $CategoriesRepo      = new CategoriesRepo();
            foreach ($reqData as $item) {
                $CategoriesValidator->validate($item, $item['id'], false);
                $CategoriesRepo->editOrder($item['cate_order'], $item['id']);
            }
        });
    }

    public function deleteByID($id)
    {
        DB::transaction(function () use ($id) {
            $CategoriesValidator = new CategoriesValidator();
            $CategoriesProcessor = new CategoriesProcessor();
            $CategoriesRepo      = new CategoriesRepo();
            $WordsRepo           = new WordsRepo();
            $ArticlesRepo        = new ArticlesRepo();
            $CategoriesValidator->validate([], $id, false);
            $children = $CategoriesRepo->findChildren($id);
            $WordsRepo->updateNullByCateID($id);    // words cate_id
            $ArticlesRepo->updateNullByCateID($id); // articles cate_id
            $CategoriesRepo->deleteByID($id);
            foreach ($children as $item) {
                $new                   = [];
                $new['cate_parent_id'] = null;
                $new['cate_order']     = $CategoriesProcessor->setOrder($CategoriesRepo, $new);
                $CategoriesRepo->editOrder($new['cate_order'], $item->id);
            }
        });
    }

}

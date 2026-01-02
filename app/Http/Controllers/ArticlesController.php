<?php
namespace App\Http\Controllers;

use App\Exceptions\Custom\RecordNotFoundException;
use App\Exceptions\Custom\Responses\Messages;
use App\Http\Requests\Articles\ArticlesRequest;
use App\Services\ArticlesService;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    protected $articlesService;

    public function __construct()
    {
        $this->articlesService = new ArticlesService();
    }

    public function find(Request $request, $id)
    {
        $result = $this->articlesService->find($id);
        if (! $result) {
            throw new RecordNotFoundException();
        }

        return response()->json($result);
    }

    public function findAll(Request $request)
    {
        $result = $this->articlesService->findAll();

        return response()->json($result);
    }

    public function add(ArticlesRequest $request)
    {
        $data = $request->validated();
        $this->articlesService->add($data);

        return Messages::Success();
    }

    public function edit(ArticlesRequest $request, $id)
    {
        $data = $request->validated();
        $this->articlesService->edit($data, $id);

        return Messages::Success();
    }

    public function deleteByID(Request $request, $id)
    {
        $this->articlesService->deleteByID($id);

        return Messages::Deletion();
    }
}

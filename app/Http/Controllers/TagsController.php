<?php
namespace App\Http\Controllers;

use App\Exceptions\Custom\RecordNotFoundException;
use App\Exceptions\Custom\Responses\Messages;
use App\Http\Requests\Tags\TagsOrderRequest;
use App\Http\Requests\Tags\TagsRequest;
use App\Services\TagsService;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    protected $tagsService;

    public function __construct()
    {
        $this->tagsService = new TagsService();
    }

    public function find(Request $request, $id)
    {
        $result = $this->tagsService->find($id);
        if (! $result) {
            throw new RecordNotFoundException();
        }

        return response()->json($result);
    }

    public function findAll()
    {
        $result = $this->tagsService->findAll();

        return response()->json($result);
    }

    public function findRecent()
    {
        $result = $this->tagsService->findRecent();

        return response()->json($result);
    }

    public function add(TagsRequest $request)
    {
        $data = $request->validated();
        $this->tagsService->add($data);

        return Messages::Success();
    }

    public function edit(TagsRequest $request, $id)
    {
        $data = $request->validated();
        $this->tagsService->edit($data, $id);

        return Messages::Success();
    }

    public function editOrder(TagsOrderRequest $request)
    {
        $data = $request->validated();
        $this->tagsService->editOrder($data);

        return Messages::Success();
    }

    public function deleteByID(Request $request, $id)
    {
        $this->tagsService->deleteByID($id);

        return Messages::Deletion();
    }

}

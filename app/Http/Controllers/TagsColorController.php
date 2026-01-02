<?php
namespace App\Http\Controllers;

use App\Exceptions\Custom\RecordNotFoundException;
use App\Exceptions\Custom\Responses\Messages;
use App\Http\Requests\TagsColor\TagsColorRequest;
use App\Services\TagsColorService;
use Illuminate\Http\Request;

class TagsColorController extends Controller
{
    protected $tagsColorService;

    public function __construct()
    {
        $this->tagsColorService = new TagsColorService();
    }

    public function find(Request $request, $id)
    {
        $result = $this->tagsColorService->find($id);
        if (! $result) {
            throw new RecordNotFoundException();
        }

        return response()->json($result);
    }

    public function findAll()
    {
        $result = $this->tagsColorService->findAll();

        return response()->json($result);
    }

    public function add(TagsColorRequest $request)
    {
        $data = $request->validated();
        $this->tagsColorService->add($data);

        return Messages::Success();
    }

    public function edit(TagsColorRequest $request, $id)
    {
        $data = $request->validated();
        $this->tagsColorService->edit($data, $id);

        return Messages::Success();
    }

    public function deleteByID(Request $request, $id)
    {
        $this->tagsColorService->deleteByID($id);

        return Messages::Deletion();
    }

}

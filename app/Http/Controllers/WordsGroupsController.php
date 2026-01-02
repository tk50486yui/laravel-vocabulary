<?php
namespace App\Http\Controllers;

use App\Exceptions\Custom\RecordNotFoundException;
use App\Exceptions\Custom\Responses\Messages;
use App\Http\Requests\WordsGroups\WordsGroupsRequest;
use App\Services\WordsGroupsService;
use Illuminate\Http\Request;

class WordsGroupsController extends Controller
{
    protected $wordsGroupsService;

    public function __construct()
    {
        $this->wordsGroupsService = new WordsGroupsService();
    }

    public function find(Request $request, $id)
    {
        $result = $this->wordsGroupsService->find($id);
        if (! $result) {
            throw new RecordNotFoundException();
        }

        return response()->json($result);
    }

    public function findAll()
    {
        $result = $this->wordsGroupsService->findAll();

        return response()->json($result);
    }

    public function add(WordsGroupsRequest $request)
    {
        $data = $request->validated();
        $this->wordsGroupsService->add($data);

        return Messages::Success();
    }

    public function edit(WordsGroupsRequest $request, $id)
    {
        $data = $request->validated();
        $this->wordsGroupsService->edit($data, $id);

        return Messages::Success();
    }

    public function deleteByID(Request $request, $id)
    {
        $this->wordsGroupsService->deleteByID($id);

        return Messages::Deletion();
    }
}

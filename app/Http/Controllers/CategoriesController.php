<?php
namespace App\Http\Controllers;

use App\Exceptions\Custom\RecordNotFoundException;
use App\Exceptions\Custom\Responses\Messages;
use App\Http\Requests\Categories\CategoriesOrderRequest;
use App\Http\Requests\Categories\CategoriesRequest;
use App\Services\CategoriesService;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    protected $categoriesService;

    public function __construct()
    {
        $this->categoriesService = new CategoriesService();
    }

    public function find(Request $request, $id)
    {
        $result = $this->categoriesService->find($id);
        if (! $result) {
            throw new RecordNotFoundException();
        }

        return response()->json($result);
    }

    public function findAll()
    {
        $result = $this->categoriesService->findAll();

        return response()->json($result);
    }

    public function findRecent()
    {
        $result = $this->categoriesService->findRecent();

        return response()->json($result);
    }

    public function add(CategoriesRequest $request)
    {
        $data = $request->validated();
        $this->categoriesService->add($data);

        return Messages::Success();
    }

    public function edit(CategoriesRequest $request, $id)
    {
        $data = $request->validated();
        $this->categoriesService->edit($data, $id);

        return Messages::Success();
    }

    public function editOrder(CategoriesOrderRequest $request)
    {
        $data = $request->validated();
        $this->categoriesService->editOrder($data);

        return Messages::Success();
    }

    public function deleteByID(Request $request, $id)
    {
        $this->categoriesService->deleteByID($id);

        return Messages::Deletion();
    }
}

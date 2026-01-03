<?php
namespace App\Http\Controllers;

use App\Exceptions\Custom\RecordNotFoundException;
use App\Exceptions\Custom\Responses\Messages;
use App\Http\Requests\Words;
use App\Services\WordsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WordsController extends Controller
{
    protected $wordsService;

    public function __construct()
    {
        $this->wordsService = new WordsService();
    }

    public function find(Request $request, $id)
    {
        $result = $this->wordsService->find($id);
        if (! $result) {
            throw new RecordNotFoundException();
        }

        return response()->json($result);
    }

    public function findAll()
    {
        $result = $this->wordsService->findAll();

        return response()->json($result);

    }

    public function search(Request $request)
    {
        $ws_name = $request->query('ws_name');
        $result  = $this->wordsService->findByName($ws_name);
        if ($result) {
            return Messages::Success();
        } else {
            return Messages::RecordNotFound();
        }
    }

    public function store(Words\WordsRequest $request)
    {
        $data = $request->validated();
        $this->wordsService->store($data);
        // event(new BroadcastUpdate(['message' => 'should be update']));

        return Messages::Success();
    }

    public function update(Words\WordsRequest $request, $id)
    {
        $data = $request->validated();
        $this->wordsService->update($data, $id);

        return Messages::Success();
    }

    public function updateCommon(Words\WordsRequest $request, $id)
    {
        $data = $request->validated();
        $this->wordsService->updateCommon($data, $id);

        return Messages::Success();
    }

    public function updateImportant(Words\WordsRequest $request, $id)
    {
        $data = $request->validated();
        $this->wordsService->updateImportant($data, $id);

        return Messages::Success();
    }

    public function deleteByID(Request $request, $id)
    {
        $this->wordsService->deleteByID($id);

        return Messages::Deletion();
    }

    /** 以下測試用，功能尚未完成 */
    public function upload(Request $request)
    {
        try {
            $uploadedFile = $request->file('ws_file');
            $fileName     = uniqid() . '_' . $uploadedFile->getClientOriginalName();
            $uploadedFile->storeAs('uploads', $fileName, 'public');
            return Messages::Success();
        } catch (\Exception $e) {
            return Messages::ProcessingFailed();
        }
    }

    public function uppyUpload(Request $request)
    {
        try {
            $fileMeta = $request->file('file');
            $fileName = uniqid() . '_' . $fileMeta->getClientOriginalName();
            $fileMeta->storeAs('uploads', $fileName, 'public');
            return Messages::Success();
        } catch (\Exception $e) {
            return Messages::ProcessingFailed();
        }
    }

    public function findUploads()
    {
        $files = Storage::disk('public')->files('uploads');

        $fileData = array_map(function ($file) {
            $fileName = pathinfo($file, PATHINFO_BASENAME);
            $fileUrl  = url(Storage::url($file));

            return [
                'file_name' => $fileName,
                'file_url'  => $fileUrl,
            ];
        }, $files);

        return response()->json($fileData, 200);
    }

    public function deleteUpload(Request $request, $id)
    {
        $fileName = $id;
        $filePath = "uploads/{$fileName}";
        try {
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
                return Messages::Deletion();
            }
            return Messages::RecordNotFound();
        } catch (\Exception $e) {
            return Messages::RecordNotFound();
        }
    }

}

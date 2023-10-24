<?php

namespace App\Http\Controllers;

use App\Services\CategoriesService;

class CategoriesController extends Controller
{
    public function findAll()
    {
        $CategoriesService= new CategoriesService();
        $result = $CategoriesService->findAll();
       
        return response()->json($result);
    }
}
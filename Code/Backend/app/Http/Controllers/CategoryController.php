<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Services\CategoryService;
use Helpers\SecurityHelper;
use Models\Category;
use Validator;

class CategoryController extends Controller {

    private $categoryService, $securityHelper;

    public function __construct(CategoryService $categoryService, SecurityHelper $securityHelper) {
        $this->categoryService = $categoryService;
        $this->securityHelper = $securityHelper;
    }


    public function index(){
        return response()->json($this->categoryService->getAll(),200);
    }

    public function show($id) {
        return response()->json($this->categoryService->getById($id),200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, Category::rules('save'));
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }
        return response()->json($this->categoryService->create($data),200);
    }

    public function update(Request $request, $id) {
        $data = $request->all();

        $validator = Validator::make($data, Category::rules('update'));
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }
        return response()->json($this->categoryService->update($id, $data),200);
    }

    public function destroy($id) {
        $category = $this->categoryService->getById($id);
        if($category == null) {
            return response()->json(['error' => "category doesn't exist"], 400);
        }
        $mastersOfCategory = $category->masters;
        if(count($mastersOfCategory) != 0){
            return response()->json(['error' => "can't delete this category, it has masters"], 400);
        }
        $this->categoryService->delete($id);
        return response()->json(['message' => 'success'],200);
    }

    public function getPagedList(Request $request) {
        $filter = (object) ($request->all());
        return response()->json($this->categoryService->getPagedList($filter),200);
    }

    public function mainCategory()
    {
        return $this->categoryService->getNestedCategories();
    }

}

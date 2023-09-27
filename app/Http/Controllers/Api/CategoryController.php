<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getData(Request $request)
    {
        $categoryData = Category::query();
        if (!empty($request['search'])) {
            $search = "%" . $request['search'] . "%";
            $categoryData->orWhere('name', 'like', $search);
        }
        $categoryData->orderBy('created_at', 'DESC');
        $Categorys = $categoryData->paginate($request['perPage']);
        return response()->json($Categorys);
    }

    public function detail($id)
    {
        $category =  Category::where('id', $id)->first();
        return response()->json($category);
    }

    public function post(Request $request)
    {
        $empData = [
            'name' => $request->name,
        ];
        $category = Category::create($empData); //create data berita
        return response()->json([
            'data' => $category,
            'message' => "Data berhasil di buat",
            'status' => 200,
        ]);
    }
    public function update(Request $request, $id)
    {
        $emp = Category::Find($id);
        $empData = [
            'name' => $request->name,
        ];
        $emp->update($empData); //update berita
        return response()->json([
            'data' => $emp,
            'message' => "Data berhasil di ubah",
            'status' => 200,
        ]);
    }

    public function delete($id)
    {
        Category::destroy($id); //delete Category berdasarkan id
        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil dihapus',
        ]);
    }
}

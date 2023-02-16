<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $req)
    {
        return Item::all();
    }

    public function show(Request $req, $id)
    {
        $category = $req->categories()->find($id);
        if (!$category instanceof Category) {
            return response()->json(
                $data = [
                    'message' => "no se encontro la categoria"
                ],
                $status = 404
            );
        }

        return response()->json($category, $status = 200);
    }

    public function store(Request $req)
    {
        $this->validate($req, [
            'name' => 'required|unique:categories'
        ]);
        try {
            $category = $req->addCategory(new Category($req->all()));

            return response()->json(
                $data = [
                    'message' => "la lista de compras fue creada exitosamente",
                    'data' => $category
                ],
                $status = 201
            );
        } catch (\Exception $e) {
            return response()->json(
                $data = [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ],
                $status = 400
            );
        }
    }

    public function update(Request $req, $categoryId)
    {
        $existingCategory = $req->cateogries()->find($categoryId);
        if (!$existingCategory instanceof Category) {
            return response()->json(
                $data = [
                    'status' => 'error',
                    'message' => "La lista de compras no existe o no fue encontrada"
                ],
                $status = 400
            );
        }

        $isCategoryDuplicate = $req->hasDuplicateCategory($req->name);
        if ($isCategoryDuplicate) {
            return response()->json(
                $data = [
                    'message' => "La lista de compras ya existe actualmente",
                    'data' => $existingCategory
                ],
                $status = 400
            );
        }

        $updatedCategory = $existingCategory->update($req->all());
        return response()->json(
            $data = [
                'message' => "La lista de compras fue actualizada correctamente!",
                'data' => $updatedCategory
            ],
            $status = 200
        );
    }

    public function destroy(Request $req, $id)
    {
        $category = Category::find($id);
        if (!$category instanceof Category) {
            return response()->json(
                $data = [
                    'message' => "La lista de compras no fue encontrada."
                ],
                $status = 400
            );
        }

        $deleteResponse = $req->deleteCategory($id);
        return response()->json(
            $data = [
                'message' => 'lista borrada',
                'data' => $deleteResponse
            ],
            $status = 200
        );
    }
}

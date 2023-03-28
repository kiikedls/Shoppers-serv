<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index(Request $req)
    {
        return Category::all();
        /*$categories=$req->User()->categories()->latest()->paginate();
        if (count($categories)) {
            return response()->json(
                $data = [
                    $categories
                ],
                $status = 200
            );
        }
        return response()->json(
            $data = [
                'message'=>'este usuario no cuenta con lista de compras actualmente'
            ],
            $status = 200
        );*/
    }

    public function show(Request $req, $id)
    {
        //$category = Category::find($id);
        $category=$req->User()->categories()->find($id);
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
            //$category = new Category();
            //$category->user_id=Auth::user()->id;
            //$category->name=$req->name;


            $category=$req->User()->addCategory(new Category($req->all()));

            return response()->json(
                $data = [
                    'message' => "la lista de compras fue creada exitosamente",
                    'data' => $category
                ],
                $status = 201
            );

            /*if ($category->save()) {
                return response()->json(
                    $data = [
                        'message' => "la lista de compras fue creada exitosamente",
                        'data' => $category
                    ],
                    $status = 201
                );
            }*/


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

    public function update(Request $req, $id)
    {
        $this->validate($req, [
            'name' => 'required|unique:categories'
        ]);
        try {
            $existingCategory = $req->User()->categories()->find($id);

            if (!$existingCategory instanceof Category) {
                return response()->json(
                    $data = [
                        'status' => 'error',
                        'message' => "La lista de compras no existe o no fue encontrada"
                    ],
                    $status = 400
                );
            }

            $isCategoryDuplicate = $req->User()->hasDuplicateCategory($req->name);
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
        } catch (\Exception $e) {
            return response()->json(
                $data = [
                    'status' => "error",
                    'data' => $e->getMessage(),
                ]
            );
        }

    }

    public function destroy(Request $req, $id)
    {
        //return $id;
        try {
            $category = $req->User()->categories()->find($id);
            if (!$category instanceof Category) {
                return response()->json(
                    $data = [
                        'message' => "La lista de compras no fue encontrada."
                    ],
                    $status = 400
                );
            }

            $deleteResponse = $req->User()->deleteCategory($id);
            return response()->json(
                $data = [
                    'message' => 'lista borrada',
                    'data' => $deleteResponse
                ],
                $status = 200
            );
        } catch (\Exception $e) {
            return response()->json(
                $data = [
                    'status' => "error",
                    'data' => $e->getMessage(),
                ]
            );
        }
    }
}

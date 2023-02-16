<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index($categoryId)
    {
        return response()->json(Item::OfCategory($categoryId)->paginate(), 200);
    }

    public function store(Request $req, $categoryId)
    {
        $this->validate($req, [
            "name"=>"required|unique:items",
            "description"=>"required|min:2",
        ]);

        $NewItem=$req->addItem(new Item($req->all()),$categoryId);
        if (!$NewItem instanceof Item) {
            return response()->json($data=[
                'status'=>'error',
                'message'=>"la lista de compras no fue encontrada"
            ],
            $status=400);
        }

        return response()->json($data=[
            'message'=>"El item fue creado exitosamente en la lista de compras",
            'data'=>$NewItem,
        ],
        $status=201);
    }

    public function show(Request $req,$categoryId,$itemId)
    {
        $category=$req->categories()->find($categoryId);
        if (!$category instanceof Category) {
            return response()->json($data=[
                'status'=>'error',
                'message'=>"La lista de compras no fue encontrada",
            ],
            $status=400);
        }

        $item=$category->items()->find($itemId);
        if (!$item instanceof Item) {
            return response()->json($data=[
                'status'=>"error",
                'message'=>"El articulo no fue encontrado en la lista de compras",
            ],
            $status=400);
        }
        return response()->json($data=$item, $status=200);
    }

    public function update(Request $req,$categoryId,$itemId)
    {
        $existingCategory=$req->categories()->find($categoryId);
        if (!$existingCategory instanceof Category) {
            return response()->json($data=[
                'status'=>"error",
                'message'=>"La lista de compras no pudo ser encontrada",
            ],
            $status=404);
        }

        $existingItem=$existingCategory->items()->find($itemId);
        if (!$existingItem instanceof Item) {
            return response()->json($data=[
                'status'=>"error",
                'message'=>"El articulo de la lista de compras no pudo ser encontrado",
            ],
            $status=404);
        }

        $existingItem->update($req->all());

        return response()->json($data=[
            'message'=>"Articulo actualizado exitosamente",
            'data'=>$existingItem
        ],
        $status=200);
    }

    public function destroy(Request $req, $categoryId,$itemId)
    {
        $existingCategory=$req->categories()->find($categoryId);
        if (!$existingCategory instanceof Category) {
            return response()->json($data=[
                'status'=>"error",
                'message'=>"La lista de compras no pudo ser encontrada",
            ],
            $status=400);
        }

        $item=$existingCategory->items()->find($itemId);

        if (!$item instanceof Item) {
            return response()->json($data=[
                'status'=>"error",
                'message'=>"El articulo de la lista de compras no pudo ser encontrado",
            ],
            $status=400);
        }

        $item->delete();
        return response()->json($data=[
            'message'=>"Articulo borrado exitosamente",
        ],
        $status=200);
    }

}

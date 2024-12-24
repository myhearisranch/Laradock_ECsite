<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    //関数の中にRequest $requestが無いとUndefined variable $request
    public function index(Request $request)
    {
         // リクエストパラメータにkeywordが入っていたら検索機能を動かす
        if($request->has('keyword')) {
            // SQLのlike句でitemテーブルを検索する
            $items = Item::where('name', 'like', '%'.$request->get('keyword').'%')->paginate(15);
        }
        else {
            $items = Item::paginate(15);
        }
        return view('item/index', ['items' => $items]);
    }

    public function show(Item $item)
    {
        return view('item/show', ['item' => $item]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        //
    }
}

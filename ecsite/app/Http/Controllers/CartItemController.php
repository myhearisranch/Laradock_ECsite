<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //updateOrCreateメソッド:
        //第一引数: 検索条件、無かった場合は新規レコードを作成する
        //第二引数: レコードが見つかった時、新規作成時に設定する値

        // レコード検索: user_id と item_id が一致するレコードを検索。
        // レコードが見つかった場合: quantity を現在の値に加算。
        // レコードが見つからなかった場合: 新しいレコードを user_id, item_id, quantity を使って作成。

        CartItem::updateOrCreate(
            [
                //ログインしているユーザーのIDを取得
                'user_id' => Auth::id(),

                //requestからitem_idを取得
                'item_id' => $request->post('item_id'),
            ],
            [
                //quantityフィールドの現在の値に$request->post('quantity')で送信された数量を加算する
                // \DB::raw()を使うことでSQLの計算式を直接書くことができる
                'quantity' => \DB::raw('quantity +' . $request->post('quantity') ),
            ]
        );
        return redirect('/')->with('flash_message', 'カートに追加しました');
    }

    /**
     * Display the specified resource.
     */
    public function show(CartItem $cartItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CartItem $cartItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CartItem $cartItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartItem $cartItem)
    {
        //
    }
}

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
    //カート内の商品データを読み込んで、ビューに渡す処理をする
    public function index()
    {
        //cart_itemsテーブルからデータを取得するクエリを作成。selectメソッドを使って取得したいカラムを指定
        $cartitems = CartItem::select('cart_items.*', 'items.name', 'items.amount')
            //user_idが現在ログインしているユーザーのIDが一致するレコードを取得する
            ->where('user_id', Auth::id())
            //cart_itemsテーブルとitemsテーブルを結合
            //cart_items テーブルと items テーブルが item_id と id で結びつけられ、商品の情報（name と amount）が一緒に取得できる。
            ->join('items', 'items.id', '=', 'cart_items.item_id')
            //指定した条件に合致する cart_items と items の情報をすべて取得する
            ->get();
       
        $subtotal = 0;

        //検索結果を一つずつ取り出して処理を行う
        foreach($cartitems as $cartitem){
            $subtotal += $cartitem->amount * $cartitem->quantity;
        }

        return view('cartitem/index', ['cartitems' => $cartitems, 'subtotal' => $subtotal]);
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

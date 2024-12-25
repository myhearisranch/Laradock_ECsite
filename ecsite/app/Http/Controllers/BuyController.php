<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//BuyクラスとMailクラスを読み込む
use App\Mail\Buy;
use Illuminate\Support\Facades\Mail;

class BuyController extends Controller
{
    public function index()
    {
        $cartitems = CartItem::select('cart_items.*', 'items.name', 'items.amount')
            ->where('user_id', Auth::id())
            ->join('items', 'cart_items.item_id', '=', 'items.id') // 修正: items_id -> cart_items.item_id
            ->get();
    
        $subtotal = 0; // セミコロンを追加
    
        foreach ($cartitems as $cartitem) {
            $subtotal += $cartitem->amount * $cartitem->quantity;
        }
    
        return view('buy/index', ['cartitems' => $cartitems, 'subtotal' => $subtotal]); // 閉じカッコを追加
    }

    public function store(Request $request)
    {
        //フォームからのリクエストパラメータにpostという値が含まれているか判定
        if( $request->has('post') ){
            //MailクラスとBuyクラスを使ってメールを送信する
            Mail::to(Auth::user()->email)->send(new Buy());
            //ログインしているユーザーが持っているカート情報を削除
            CartItem::where('user_id', Auth::id())->delete();
            return view('buy/complete');
        }
        $request->flash();
        return $this->index();
    }
}

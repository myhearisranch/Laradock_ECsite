<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    //編集できるカラムを指定する
    //createメソッドを使ってカート情報にユーザーID、商品ID、数量を登録できるようにする
    protected $fillable = ['user_id', 'item_id', 'quantity'];
}

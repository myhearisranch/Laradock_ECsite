@extends('layouts.app')

@section('content')
  @if(Session::has('flash_message'))
   <div class="alert alert-success">
    {{ session('flash_message' )}}
   </div>
  @endif

  <div class="cantainer">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          @foreach ($cartitems as $cartitem)
            <div class="card-header">
              <a href="/item/{{ $cartitem->item_id }}">{{ $cartitem->name }}</a>
            </div>
            <div class="card-body">
              <div>
                {{ $cartitem->amount }}円
              </div>
              <div class="form-inline">
                <!-- /cartitem/{{ $cartitem->id }}とし、カートデータのIDをパスに付与 コントローラ側でidに対応したカート情報を受け取れる -->
                <form method="POST" action="/cartitem/{{ $cartitem->id }}">
                  @method('PUT')
                  @csrf
                  <!-- value属性に{{ $cartitem->quantity }}を指定: 既存のデータの数量をあらかじめ表示する -->
                  <input type="text" class="form-control" name="quantity" value="{{ $cartitem->quantity }}">
                    個
                  <button type="submit" class="btn btn-primary">更新</button>
                </form>
                <!--
                  フォームのaction属性には、削除対象となるcartItemのidがURLに組み込まれいる。
                  例えば、$cartitem->idが123の場合、actionは/cartitem/123となります。
                  これを使ってweb.phpのルーティングで処理を行う。

                  $cartitem->idはどうやって定義されるのか?
                  @foreach ($cartitems as $cartitem) {$cartitemsはコントローラのindexアクションで定義}
                  $cartitem->idで$cartitemのidが参照される
                -->
                <form method="POST" action="/cartitem/{{ $cartitem->id }}">
                  @method('DELETE')
                  @csrf
                  <button type="submit" class="btn btn-primary ml-1">カートから削除する</button>
                </form>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <div class = "col-md-4">
        <div class= "card">
          <div class="card-header">
            小計
          </div>
          <div class = "card-body">
            {{ $subtotal }}円
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
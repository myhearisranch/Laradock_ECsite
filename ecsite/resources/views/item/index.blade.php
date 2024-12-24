@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-left">
        @foreach($items as $item)
          <div class="col-md-4 mb-2">
            <div class="card-header">{{ $item->name }}</div>
            <div class="card-body">
              {{ $item->amount }}
            </div>
          </div>
        @endforeach
    </div>
    <div class="row justify-content-center">
      <!-- Request::get('keyword') HTTPリクエストからkeywordの値を取得し、ページ遷移の検索結果を保持する -->
      {{ $items->appends(['keyword' => Request::get('keyword')])->links() }}
    </div>
  </div>
@endsection
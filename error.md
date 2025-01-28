# 1. php artisan migrateでエラーが発生し、マイグレーションを実行できない

## エラーメッセージ
~~~
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, SQL: select exists (select 1 from information_schema.tables where table_schema = 'default' and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connection.php:825
    821▕                     $this->getName(), $query, $this->prepareBindings($bindings), $e
    822▕                 );
    823▕             }
    824▕
  ➜ 825▕             throw new QueryException(
    826▕                 $this->getName(), $query, $this->prepareBindings($bindings), $e
    827▕             );
    828▕         }
    829▕     }

      +42 vendor frames

  43  artisan:13
      Illuminate\Foundation\Application::handleCommand()
~~~

## ログ:
~~~
2025-01-27T12:18:29.899361Z 0 [ERROR] [MY-000067] [Server] unknown variable 'default_authentication_plugin=mysql_native_password'.
2025-01-27T12:18:29.900062Z 0 [ERROR] [MY-010119] [Server] Aborting
~~~

## 原因

MySQL 8.0 と MySQL 5.7 の間で認証プラグインの設定に関する互換性の違い

・MySQL 8.0 では、default_authentication_plugin 設定がサポートされていない
・MySQL 5.7 では mysql_native_password がデフォルト認証プラグイン

## 解決方法

laradockの.envを編集し使用するMySQLのバージョンを5.7に変更

# 2. カートに入れるボタンを押すと、SQLSTATE[HY000]: General error: 1 no such column: quantity (Connection: sqlite, SQL: insert into "cart_items" ("user_id", "item_id", "quantity", "updated_at", "created_at") values (1, 1, quantity + 4, 2025-01-28 06:03:54, 2025-01-28 06:03:54))というエラーが発生する

## エラーメッセージ
~~~
SQLSTATE[HY000]: General error: 1 no such column: quantity (Connection: sqlite, SQL: insert into "cart_items" ("user_id", "item_id", "quantity", "updated_at", "created_at") values (1, 1, quantity + 4, 2025-01-28 06:03:54, 2025-01-28 06:03:54))
~~~

~~~CartItemController.php
public function store(Request $request)
    {
        CartItem::updateOrCreate(
            [
              'user_id' => Auth::id(),

              'item_id' => $request->post('item_id'),
            ],
            [
              'quantity' => \DB::raw('quantity +' . $request->post('quantity') ),
            ]
        );
        return redirect('/')->with('flash_message', 'カートに追加しました');
    }
~~~

## 原因
updateOrCreate の第二引数は、「レコードが新規作成される場合にのみ使用される初期値」を期待している。
しかし、DB::raw('quantity +' .)のようなSQL式を渡すと、updateOrCreate はそのまま文字列として quantity に代入しようとします。その結果、無効な SQL クエリが生成される。エラーメッセージからも、quantity + 4 という文字列がそのまま SQL に渡されていることが分かる。

## 解決方法
~~~CartItemController.php
public function store(Request $request)
        {
            $itemId = $request->post('item_id');
            $quantityToAdd = (int) $request->post('quantity');
            $userId = Auth::id();

            // レコードを作成または取得
            $cartItem = CartItem::updateOrCreate(
                [
                    'user_id' => $userId,
                    'item_id' => $itemId,
                ],
                [
                    'quantity' => 0, // デフォルト値を0に設定
                ]
            );

            // 数量を加算して保存
            $cartItem->quantity += $quantityToAdd;
            $cartItem->save();

            // フラッシュメッセージを設定してリダイレクト
            return redirect('/')->with('flash_message', 'カートに追加しました');
        }

~~~
解決方法：
第二引数では quantity に固定値 (0) を指定し、新規作成時の動作を確定させた。
その後、加算処理を別途明示的に行う形に修正した。



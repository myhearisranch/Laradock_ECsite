# php artisan migrateでエラーが発生し、マイグレーションを実行できない

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
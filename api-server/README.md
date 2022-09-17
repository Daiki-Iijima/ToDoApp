# サーバーについて

このAPIサーバーは、PHP(Slim)とMySQLを使用します。

ライブラリの実態はGitの管理に含まれていないので、新規でクローンした場合は、以下のコマンドでライブラリ周りを落としてください

```sh
composer install
```

## DB

MySQLを使用しています。以下のコマンドを順番に実行してください

### 0. ログイン

```sh
mysql -uユーザー -pパスワード
```

### 1. データベースの作成

ここからは、コンソールに`mysql>`が表示され入力待ちになっている状態を前提にします。

- データベースの作成

    ```sh
    CREATE DATABASE todo_db;
    ```

### 2. 使用データベースの切り替え

```sh
USE todo_db;
```

### 3. テーブルの作成

以下のSQL文を実行してください。

- 実行するSQL文

    ```sql
    > CREATE TABLE todo (
    > id INT unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    > group_name VARCHAR(30) NOT NULL,
    > title VARCHAR(100) NOT NULL,
    > decription VARCHAR(200) NOT NULL,
    > date DATE NOT NULL
    > );
    ```

- 自分でテーブルを作成する場合は予約語を使用していなかなどをチェックしたほうがいいです。予約語を無意識に使っていて謎のエラーにさいなまれる場合があります。

  [mysqlの予約語一覧](https://dev.mysql.com/doc/refman/5.6/ja/reserved-words.html#reserved-words-5-6-G)

- 正常にテーブルが生成できたか確認する

    ```sql
    DESCRIBE todo;
    ```

    ```txt
    +-------------+--------------+------+-----+---------+----------------+
    | Field       | Type         | Null | Key | Default | Extra          |
    +-------------+--------------+------+-----+---------+----------------+
    | id          | int unsigned | NO   | PRI | NULL    | auto_increment |
    | group_name  | varchar(50)  | NO   |     | NULL    |                |
    | title       | varchar(100) | NO   |     | NULL    |                |
    | description | varchar(200) | NO   |     | NULL    |                |
    | date        | date         | NO   |     | NULL    |                |
    +-------------+--------------+------+-----+---------+----------------+
    ```

### 4. テストデータの挿入(任意)

データの検証のためにテストデータを挿入します。

```sql
INSERT INTO todo ( group_name, title, description, date) VALUES 
( 'Test', 'テストタスク', 'このタスクはテストタスクです', '2022-09-17');
```

### 5. テストデータの確認テスト

データの確認を行います。

```sql
SELECT * FROM todo;
```

## テスト

### テスト用サーバーの起動方法

ローカルホストでテスト環境動作の場合、`publicフォルダ`をルートフォルダとしてサーバーに公開します。

```sh
php -S localhost:8888 -t public
```

### リクエスト一覧

- GET
  - `http://localhost:8888/todo/all`
- POST
  - `http://localhost:8888/todo/add`
    - body

        ```json
        {
          "group_name" : "テストタイトル3",
          "title" : "追加タイトル3",
          "description" : "これは追加のタイトルです",
          "date" : "2022-09-12"
        }
        ```

- PUT
  - `http://localhost:8888/todo/update/(id)`
    - body

        ```json
        {
          "group_name" : "テストタイトル3",
          "title" : "追加タイトル3",
          "description" : "これは追加のタイトルです",
          "date" : "2022-09-12"
        }
        ```

- DEL
  - `http://localhost:8888/todo/delete/(id)`

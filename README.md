# Narnia for Laravel

## 目的
* Laravel は、様々なことが出来る分、書き方を統一することが難しい
* また、Modelまわりのキャッシュやコントローラ制御など、共通化できる部分も多い
* 通常のWebアプリケーションの構造を形式化し、開発の効率を爆速にする


## 利用しているライブラリ・フレームワーク

* [Laravel] (https://laravel.com/) - Laravel


## コーディング規約

PSR-2に準拠


## セットアップ、インストール方法

Laravelで使われているパッケージを使用するため、Composerを導入している。
`composer` コマンドが使用できる環境で、プロジェクトルートにて
`composer install` することで `/vendor` 以下にパッケージがインストールされる。


## プロジェクトディレクトリ構成

### ルーティング

### コントローラ
#### セッション管理

### モデル

### ビュー


## 処理の流れ（改修例）

改修対象 基本情報管理 > SHOPマスタ `admin/basis/index.php`

1. `admin/basis/index.php` → `admin/basis/index` でアクセスできるように、リンクを変更。

編集するファイル

* `html/admin/basis/index.php` があれば削除する。(特殊な処理をしていないかだけは確認する)
* `data/constants_view.php` にて `admin/basis/index` へのパスを定数化
* `admin/basis/index.php` にリンク、リダイレクトしている全てのファイル にて その定数に置き換える

注意: リダイレクトについては使う関数を変えないと `.php` を消したことによって正常に動作しないことがある。

2. ルーティング設定

`html/routes.php` にて ルートを設定 `$app['router']->get('/admin/basis/index', 'AdminBasisIndexController@index');`

3. コントローラを作成

* `data/class/pages/admin/basis/LC_Page_Admin_Basis.php` をコピーして `data/class/Controllers/AdminBasisIndexController.php` とする
* 継承親を `data/class/Controllers/Controller.php` とする
* init(), process()をまとめてindex()とする。(ページ遷移時に呼ばれる関数が一意なのであえてコンストラクタは使わない)
* 新Viewクラスを使用するように `$objView = new SC_AdminView();` -> `$objView = new AdminView();`

4. モデルを(作成,)編集

モデルは `data/class/Models` 以下にある。

* コントローラを綺麗にしつつ、モデルでやるべき処理を各モデルに移す。 対応するモデルが無ければ作成。

5. テンプレートをマークアップし直す。

テンプレートファイル `/admin/basis/index.tpl`
定義されている定数は `/constants_view.php` に定義し直しておく。



## FAQ

### 修正対象のページのURLはわかってるけど、どのファイルを編集すればいいのかわからない

### PHPプログラム中に知らないクラスが出てきたけど、どこでそのクラスが定義されてるのかわからない

### 定数が出てきたけど、どこで定義されているのかわからない

### CSSを編集したい

### ファイルを書き込んだり読み込んだりするページがちゃんと動かない

* 書き込み・読み込み対象のディレクトリ/ファイルが存在しているかどうか確認しましょう
* そのディレクトリ・ファイルのパーミッションが、Apacheの実行ユーザで書き込み・読み込みすることができるようになっているか確認しましょう

### ユニットテストを書きたい

[/tests/README.md](/tests/README.md)を読んで下さい。


# PHPStan-CakePHP2 ドキュメント

pieceofcake2/phpstan-cakephp2 は、[pieceofcake2/cakephp](https://github.com/pieceofcake2/cakephp) で動作する
CakePHP 2 のアプリケーションとプラグインのための [PHPStan](https://phpstan.org/) 拡張です。プロパティとして読み込まれる
モデル、コンポーネント、タスク、モデルに対して呼び出されるビヘイビアのメソッド、`ClassRegistry::init()` といった
CakePHP 2 のマジックを PHPStan に教えます。[sidz/phpstan-cakephp2](https://github.com/sidz/phpstan-cakephp2)
のフォークです。

- [インストール](#installation)
- [ビヘイビアとスキーマの場所](#behavior-and-schema-locations)
- [機能](#features)
- [スタブ](#stubs)
- [開発](#development)

<a id="installation"></a>

## インストール

[Composer](https://getcomposer.org/) で、開発用の依存パッケージとして拡張をインストールします:

```bash
composer require --dev pieceofcake2/phpstan-cakephp2
```

PHPStan の設定で拡張を読み込みます:

```yaml
# phpstan.neon
includes:
  - vendor/pieceofcake2/phpstan-cakephp2/extension.neon
```

[phpstan/extension-installer](https://github.com/phpstan/extension-installer) を使っている場合は、拡張が
自動的に読み込まれるので、この設定は必要ありません。

[pieceofcake2/app](https://github.com/pieceofcake2/app) スケルトンには、この拡張がすでに設定されています。

<a id="behavior-and-schema-locations"></a>

## ビヘイビアとスキーマの場所

ビヘイビアとスキーマファイルは、PHPStan を実行するディレクトリ (通常はプロジェクトルート) を基準にした glob パターンで
探されます。`extension.neon` のデフォルトは次の場所を対象にしています:

- pieceofcake2/cakephp のコアのビヘイビア (`vendor/pieceofcake2/cakephp/src/Cake/Model/Behavior` と、
  古いリリースや CakePHP 自体を解析するときに使われる `lib/Cake` と `src/Cake` のパス)
- pieceofcake2/app のモダン構成: `src/Model/Behavior`、`plugins/*/Model/Behavior` と `config/Schema`
- CakePHP 2 のレガシー構成: `app/Model/Behavior`、`app/Plugin/*/Model/Behavior` と `app/Config/Schema`

ビヘイビアのクラスやスキーマファイルが他の場所 (たとえば vendor ディレクトリ) にある場合は、その場所を設定に追加してください。
追加した場所はデフォルトとマージされます。例:

```yaml
# phpstan.neon
parameters:
  ModelBehaviorsExtension:
    behaviorPaths:
      - vendor/my-vendor/my-plugin/src/Model/Behavior/*.php
  SchemaService:
    schemaPaths:
      - vendor/my-vendor/my-plugin/config/Schema/*.php
```

<a id="features"></a>

## 機能

PHPStan に次の機能が追加されます:

- ビヘイビアのメソッドを、すべてのモデルの追加のメソッドとして扱います (`$model->behaviorMethod()`)。
- モデルクラスの名前を持つコントローラー、モデル、シェルのプロパティを、それらのクラスのインスタンスとして扱います
  (`$controller->Model`)。
- `$components` に列挙されたコンポーネントの名前を持つコントローラーとコンポーネントのプロパティを、それらのクラスの
  インスタンスとして扱います (`$controller->Component`、`$component->Component`)。
- タスククラスの名前を持つシェルのプロパティを、それらのクラスのインスタンスとして扱います (`$shell->Task`)。
  シェルやタスクの `$tasks` に列挙されたタスクは、同じ名前のモデル (たとえば bake の `Model` タスク) より優先されます。
- `@property` タグで宣言されたプロパティは PHPStan に任せ、この拡張が追加するプロパティよりタグが優先されるようにします。
- `ClassRegistry::init($className)` の戻り値を、可能であれば `$className` のインスタンスとして、スキーマファイルに
  テーブルだけが定義されている場合は `Model` として扱います。クラス名はプラグイン記法 (`'Plugin.Model'`) でも、
  `class` キーを持つ配列 (`['class' => 'Plugin.Model', 'alias' => 'Alias']`) でも指定できます。その他のクラス名
  (たとえば `string` 型の変数) は `object` になります。`$strict` なしで 1 つのクラスを指定した場合、`init()` が false を
  返すことはないからです (クラスが存在しなければ `AppModel` を作成します)。`$strict = true` の場合、`init()` はクラスが
  存在しないかモデルでなければ false を返すので、自身のインスタンスになるのはモデルクラスだけで、それ以外は
  `object|false` になります。設定のリストと、そうしたリストである可能性のある値 (たとえば `mixed`) は、宣言どおりの
  `bool|object` のままです。
- `$this->Components->load('Name')` の戻り値を `NameComponent` のインスタンスとして扱います。

たとえば PHPStan は、次のすべてのプロパティと呼び出しの型を把握します:

```php
class PostsController extends AppController
{
    public $components = ['Paginator'];

    public function index()
    {
        // Post (a model class), PaginatorComponent (listed in $components)
        $this->set('posts', $this->Paginator->paginate($this->Post));

        // TreeBehavior::generateTreeList(), called on the model
        $this->set('categories', $this->Post->Category->generateTreeList());

        // Tag
        $Tag = ClassRegistry::init('Tag');
        $this->set('tags', $Tag->find('list'));
    }
}
```

<a id="stubs"></a>

## スタブ

この拡張には、`Model`、`Router`、`ClassRegistry` のより正確な型と、`basics.php` のグローバルな翻訳関数
(`__()`、`__n()`、...) の宣言を含む PHPStan のスタブも同梱されています。これらは `extension.neon` が読み込みます。

<a id="development"></a>

## 開発

```bash
composer install

# Run the tests
vendor/bin/phpunit

# Run static analysis
vendor/bin/phpstan

# Check the coding standard (cakephp/cakephp-codesniffer), and fix what can be fixed automatically
vendor/bin/phpcs
vendor/bin/phpcbf
```

同じチェックは Composer スクリプトとしても使えます: `composer phpunit`、`composer phpstan`、`composer cs-check`、
`composer cs-fix`、そしてテスト、静的解析、コーディング規約のチェックを一度に実行する `composer test` です。

<a id="origin"></a>

## 由来

このパッケージは、[sidz/phpstan-cakephp2](https://github.com/sidz/phpstan-cakephp2) (Packagist では
`sidz/phpstan-cakephp2`、最後のアップストリームのバージョンは 0.2.0) を保守しているフォークで、元々は Oleg Zhulnev
によるものです。モダンな PHP で動く CakePHP 2 のために [pieceofcake2](https://github.com/pieceofcake2) が保守しています。
元のコードからの変更点は
[フォーク以降のコミット履歴](https://github.com/pieceofcake2/phpstan-cakephp2/compare/21673aa...main)
を参照してください。

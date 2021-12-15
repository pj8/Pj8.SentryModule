# Pj8.SentryModule

[Sentry](https://docs.sentry.io/platforms/php/) を [BEAR.Sunday](http://bearsunday.github.io/) アプリケーションで利用するためのモジュール

![Continuous Integration](https://github.com/pj8/pj8.sentrymodule/workflows/Continuous%20Integration/badge.svg)

## 機能

* BEAR.Sunday アプリケーションでの [Sentry PHP SDK](https://github.com/getsentry/sentry-php) の設定
* Sentry のエラー監視、パフォーマンスモニタリングへの統合を提供

## インストール

[Composer](https://getcomposer.org/) でプロジェクトにインストールします。

```bash
composer require pj8/sentry-module
```

## アプリケーションへの適用

- モジュールインストール

```php
use BEAR\Package\AbstractAppModule;
use BEAR\Package\Context\ProdModule as PackageProdModule;
use Pj8\SentryModule\SentryModule;
use Pj8\SentryModule\SentryErrorModule;

class ProdModule extends AbstractAppModule
{
    protected function configure()
    {
        $this->install(new PackageProdModule());
        $this->install(new SentryModule(['dsn' => 'https://secret@sentry.example.com/1"']));
        $this->install(new SentryErrorModule($this));
    }
}
```

### モジュールインストールの注意事項

SentryModule はエラーをキャプチャーするために以下のインターフェイスの束縛を上書きします。

- `\BEAR\Sunday\Extension\Error\ErrorInterface`
- `\BEAR\Sunday\Extension\Error\ThrowableHandlerInterface`

そのため、既にプロジェクト独自のエラーハンドラーが束縛されている場合は SentryModule のエラーキャプチャー機能が動作しない場合があります。
束縛の順序やコンテキストごとのモジュール設定など確認してください。

## パフォーマンスモニタリング

- `Monitorable` アノテーションを使うと任意の処理を計測することができます

## BEAR.Resource のパフォーマンスモニタリング

- 下記のようにオプションモジュール `ResourceMonitorModule` をインストールするとリソースの処理時間を計測することができます

```php
        $this->install(new SentryModule(['dsn' => 'https://secret@sentry.example.com/1"']));
        $this->install(new ResourceMonitorModule());
```

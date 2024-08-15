## Rese（リーズ）飲食店予約サービスアプリ

### サービス概要

- 飲食店予約サービスRese（リーズ）は、ある企業のグループ会社向けの飲食店予約サービスです。外部サービスに依存せず、手数料を節約しながら自社で完全にコントロール可能な予約システムを提供します。


## 制作の背景と目的
外部の飲食店予約サービスでは手数料が発生するため、自社で予約サービスを持つことでコスト削減とデータ管理の自由度を高めたいと考えています。

## 制作の目標
- 初年度でのユーザー数10,000人を達成する。
- 直感的で使いやすいインターフェースを提供する。

## 機能要件
- **予約変更機能**: ユーザーはマイページから予約日時や人数を変更できる。
- **評価機能**: 来店後、ユーザーが店舗を5段階で評価し、コメントを残せる。
- **バリデーション**: 認証と予約の際にFormRequestを使用してバリデーションを行う。
- **レスポンシブデザイン**: タブレット・スマートフォン用にブレイクポイント768pxでレスポンシブデザインを実装。
- **管理画面**: 管理者、店舗代表者、利用者の3種類の権限に基づく管理画面を提供。
- **ストレージ**: 店舗の画像をストレージに保存。
- **認証**: メールによる本人確認機能。
- **メール送信**: 管理画面から利用者にお知らせメールを送信。
- **リマインダー**: タスクスケジューラーを利用して、予約当日の朝にリマインダーを送信。
- **QRコード**: 利用者が来店時に提示するQRコードを発行し、店舗側で照合。
- **決済機能**: Stripeを利用した決済機能。

## 作業範囲
- 設計
- コーディング
- テスト

## ターゲットユーザー
- 20〜30代の社会人

## システム要件
- **開発言語**: PHP
- **フレームワーク**: Laravel
- **データベース**: MySQL
- **バージョン管理**: GitHub


## 使用技術
- **フロントエンド**: HTML, CSS, JavaScript
- **バックエンド**: PHP, Laravel
- **データベース**: MySQL
- **バージョン管理**: Git, GitHub


## コントリビューション
このプロジェクトはクローズドソースであり、特定のグループ会社の内部使用に限られています。外部からのコントリビューションは受け付けていません。

## ライセンス
このプロジェクトは特定のクライアントにのみ提供される専用のソフトウェアです。再配布や商用利用は禁止されています。
### 使用技術（実行環境）

- **開発言語**: PHP
- **フレームワーク**: Laravel 8.x
- **データベース**: MySQL
- **バージョン管理**: GitHub
- **コンテナ化技術**: Docker

### テーブル設計・ER図

![rese_table ER Diagram](src/rese_table.drawio.png)

![rese ER Diagram](src/rese.drawio.png)
### 環境構築

- **PHP**: 8.1.29
- **MySQL**: 10.11.6-MariaDB
- **Composer**: 2.7.7
- **Docker**: 26.1.4
- **Laravel Framework**: 8.83.27


- ＊ご使用のPCに合わせて各種必要なファイル(.envやdocker-compose.yml等)は作成、編集してください。

- **1.docker-compose exec bash**
- **2.composer install**
- **3..env.exampleファイルから.envを作成し、環境変数を変更**
- **4.php artisan key:generate**
- **5.php artisan migrate**
- **6.php artisan db:seed**


#### HTTPS 証明書の発行方法
QRコードを照合するためにカメラにアクセスしますが、https環境であることが条件です。
HTTPS通信を行うためにはSSL証明書が必要です。以下のコマンドを使用して自己署名のSSL証明書を生成できます。

Bash

openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout nginx.key -out nginx.crt

このコマンド実行時には、以下のような情報を入力します。

- 国名 (2文字コード)
- 州または県名
- 市区町村名
- 組織名
- 組織内部名
- 共通名（サーバのFQDNまたはあなたの名前）
- メールアドレス

例えば、以下のように入力します。

Country Name (2 letter code) [AU]: JP
State or Province Name (full name) [Some-State]: 都道府県
Locality Name (eg, city) []: 市町村
Organization Name (eg, company) [Internet Widgits Pty Ltd]: 会社名等
Common Name (e.g. server FQDN or YOUR name) []: ponponmama　名前
Email Address []: yourmail@gmail.com　　メールアドレス

このコマンドにより、`nginx.key` (秘密鍵) と `nginx.crt` (公開証明書) が生成されます。生成時にはいくつかの質問に答える必要があります。


#### Docker 環境設定

`docker-compose.yml` ファイルを使用して、Docker環境を構築します。HTTPS用のポート443を開放し、SSL証明書と秘密鍵を適切な場所にマウントします。

#### docker-compose.ymlを編集

ports:
      - "443:443" # HTTPS用のポートを追加
volumes:      
- ./path/to/your/nginx.crt:/path/to/your/nginx.crt # SSL証明書をマウント
- ./path/to/your/nginx.key:/path/to/your/nginx.key # 秘密鍵をマウント

####default.confを編集
listen 443 ssl;
ssl_certificate /path/to/your/ssl/nginx.crt;　 # SSL証明書へのパスを更新
ssl_certificate_key /path/to/your/ssl/nginx.key;　 # 秘密鍵へのパスを更新

####リマインダーメールを送るために必要なCronジョブの設定手順

#####Laravel スケジューラを利用するためには、Cronジョブの設定だけでなく、Laravelのスケジューラを適切に設定する必要があります。以下に、Laravelのスケジューラ設定の完全な手順を示します。

- Laravel スケジューラの設定

Laravelのスケジューラを使用するには、app/Console/Kernel.php ファイル内でスケジュールされたタスクを定義する必要があります。以下は、Kernel.php ファイルにスケジュールを設定する方法の例です。

protected function schedule(Schedule $schedule)
    {
        // ここにスケジュールされたコマンドを追加します。
        $schedule->command('inspire')
                 ->hourly();

        // 予約リマインダーメールを毎日朝7時に送信するスケジュール
        $schedule->command('send:reservation-reminder')
                 ->dailyAt('07:00')
                 ->appendOutputTo(storage_path('logs/reservation_reminder.log'));
    }

この設定では、send:reservation-reminder コマンドが毎日7時に実行され、その実行結果が storage/logs/reservation_reminder.log に記録されます。appendOutputTo メソッドを使用して、コマンドの出力をログファイルに追記するように設定しています。


1. Cronジョブの編集
-コンテナ内で以下のコマンドを実行してCronジョブを編集します。

Bash

crontab -e

2. Cronジョブの追加
エディタが開いたら、以下の行を追加してください。

Bash

* * * * * cd /var/www && php artisan schedule:run >> /dev/null 2>&1

-この設定は、毎分 /var/www ディレクトリに移動し、php artisan schedule:run コマンドを実行します。出力は /dev/null にリダイレクトされ、エラーも同様に捨てられます。
このCronジョブは、毎分Laravelのschedule:runコマンドを実行し、Laravelのスケジューラによって定義されたタスクを処理します。
これで、Laravel スケジューラが正しく設定され、定期的に実行されるようになります。

3. Cronサービスの確認と起動
Cronサービスが動作しているかを確認します。

Bash

service cron status

動作していない場合は、以下のコマンドでCronを起動してください。

Bash

service cron start

4. Cronのインストール
Cronがインストールされていない場合は、以下の手順でインストールします。

Bash

apt-get update
apt-get install cron -y

5. Dockerfileへの追加
Dockerコンテナを再構築する際に毎回Cronをインストールするのは非効率的です。そのため、Cronのインストールと設定をDockerfileに追加することをお勧めします。

FROM php:7.4-fpm

### 必要なパッケージのインストール
RUN apt-get update && apt-get install -y cron

### Laravel スケジューラ用のクロンジョブ設定
RUN echo "* * * * * cd /var/www && php artisan schedule:run >> /dev/null 2>&1" | crontab -

### cron サービスの起動
CMD ["cron", "-f"]

6. エディタのインストール
Cronジョブを編集するためのエディタが必要です。`nano` や `vim` をインストールすることができます。

Bash

apt-get install nano -y
### または
apt-get install vim -y

Laravel スケジューラが毎分実行され、定義されたタスクが自動的に処理されるようになります。
Laravel スケジューラがコンテナ内で正しく設定され、定期的に実行されるようになります。



### URL
- **開発環境:** [https://localhost/](https://localhost/)
- **phpMyAdmin:** [http://localhost:8080/](http://localhost:8080/)


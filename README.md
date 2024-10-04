### Rese（リーズ）飲食店予約サービスアプリ

#### サービス概要

- 飲食店予約サービス Rese（リーズ）は、ある企業のグループ会社向けの飲食店予約サービスです。外部サービスに依存せず、手数料を節約しながら自社で完全にコントロール可能な予約システムを提供します。

### 制作の背景と目的

外部の飲食店予約サービスでは手数料が発生するため、自社で予約サービスを持つことでコスト削減とデータ管理の自由度を高めたいと考えています。

### 制作の目標

- 初年度でのユーザー数 10,000 人を達成する。
- 直感的で使いやすいインターフェースを提供する。

### 機能要件

- **予約変更機能**: ユーザーはマイページから予約日時や人数を変更できる。
- **評価機能**: 来店後、ユーザーが店舗を 5 段階で評価し、コメントを残せる。
- **バリデーション**: 認証と予約の際に FormRequest を使用してバリデーションを行う。
- **レスポンシブデザイン**: タブレット・スマートフォン用にブレイクポイント 768px でレスポンシブデザインを実装。
- **管理画面**: 管理者、店舗代表者、利用者の 3 種類の権限に基づく管理画面を提供。
- **ストレージ**: 店舗の画像をストレージに保存。
- **認証**: メールによる本人確認機能。
- **メール送信**: 管理画面から利用者にお知らせメールを送信。
- **リマインダー**: タスクスケジューラーを利用して、予約当日の朝にリマインダーを送信。
- **QR コード**: 利用者が来店時に提示する QR コードを発行し、店舗側で照合。
- **決済機能**: Stripe を利用した決済機能。

### 作業範囲

- 設計
- コーディング
- テスト

### ターゲットユーザー

- 20〜30 代の社会人

### システム要件

- **開発言語**: PHP
- **フレームワーク**: Laravel
- **データベース**: MySQL
- **バージョン管理**: GitHub

### 使用技術

- **フロントエンド**: HTML, CSS, JavaScript
- **バックエンド**: PHP, Laravel
- **データベース**: MySQL
- **バージョン管理**: Git, GitHub

### コントリビューション

このプロジェクトはクローズドソースであり、特定のグループ会社の内部使用に限られています。外部からのコントリビューションは受け付けていません。

### ライセンス

このプロジェクトは特定のクライアントにのみ提供される専用のソフトウェアです。再配布や商用利用は禁止されています。

#### 使用技術（実行環境）

- **開発言語**: PHP
- **フレームワーク**: Laravel 8.x
- **データベース**: MySQL
- **バージョン管理**: GitHub
- **コンテナ化技術**: Docker

#### テーブル設計
![rese_table ER Diagram](src/rese_table.drawio.png)

#### ER 図

![rese ER Diagram](src/rese.drawio.png)

#### 環境構築

- **PHP**: 8.1.29
- **MySQL**: 10.11.6-MariaDB
- **Composer**: 2.7.7
- **Docker**: 26.1.4
- **Laravel Framework**: 8.83.27

- ＊ご使用の PC に合わせて各種必要なファイル(.env や docker-compose.yml 等)は作成、編集してください。

- **1.docker-compose exec bash**
- **2.composer install**
- **3..env.example ファイルから.env を作成し、環境変数を変更**
- **4.php artisan key:generate**
- **5.php artisan migrate**
- **6.php artisan db:seed**

####クローン作製手順

1. GitHub リポジトリのクローン

```bash
git clone https://github.com/ponponmama/20240713-erika_hara-rese.git
cd 20240713-erika_hara-rese
```

2. 必要なパッケージのインストール

```bash　
sudo apt-get update
sudo apt-get install php-curl
```

Docker コンテナのビルドと起動

```bash
docker-compose up -d --build
```

- php.ini ファイルで curl 拡張機能を有効にする

```ini
extension=curl
```

- curl 拡張機能が正しくロードされているか確認

```bash
php -m | grep curl
```

3. Composer を使用した依存関係のインストール

- Docker 環境で PHP コンテナに入り、依存関係をインストールします。

```bash
docker-compose exec php bash
composer install
```

4. 環境設定ファイルの設定

- .env.example ファイルを .env としてコピーし、必要に応じてデータベースなどの設定を行います。

```bash
cp .env.example .env
```

- 環境設定を更新した後、設定キャッシュをクリアするために以下のコマンドを実行します。これにより、新しい設定がアプリケーションに反映されます。

```bash
docker-compose exec php bash
php artisan config:clear
```

この手順は、特に環境変数が更新された後や、`.env` ファイルに重要な変更を加えた場合に重要です。設定キャッシュをクリアすることで、古い設定が引き続き使用されることを防ぎます。

5. 環境設定手順

#### HTTPS 証明書の発行方法

- QR コードを照合するためにカメラにアクセスしますが、https 環境であることが条件です。

HTTPS 通信を行うためには SSL 証明書が必要です。以下のコマンドを使用して自己署名の SSL 証明書を生成できます。
この証明書は開発環境でのテスト用途に適しています。

GitHub クローンには下記の証明書は含まれていないため、作成してください。

プロジェクトのルートディレクトリ（コンテナ内）で以下のコマンドを実行してください。

```bash
openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout nginx.key -out nginx.crt
```

このコマンド実行時には、以下のような情報を入力します。

- 国名 (2 文字コード)
- 州または県名
- 市区町村名
- 組織名
- 組織内部名
- 共通名（サーバの FQDN またはあなたの名前）
- メールアドレス

例えば、以下のように入力します。

#### SSL 証明書生成のための情報入力例

- **Country Name (2 letter code)**: `JP` (日本)
- **State or Province Name (full name)**: `Hyogo` (兵庫県)
- **Locality Name (city)**: `Kobe` (神戸市)
- **Organization Name (company)**: `Rese Inc.` (リーズ株式会社)
- **Organizational Unit Name (eg, section) []**:無い場合は空白でも OK
- **Common Name (server FQDN or YOUR name)**: `localhost` (開発環境やテスト環境での一般的な設定)
- **Email Address**: `yourmail@gmail.com` (連絡先メールアドレス)

これらの情報は、SSL 証明書を生成する際に必要とされるものです。実際に証明書を生成する際には、適切な値を入力してください。

このコマンドにより、`nginx.key` (秘密鍵) と `nginx.crt` (公開証明書) が生成されます。生成時には上記のようにいくつかの質問に答える必要があります。

#### Docker 環境設定

`docker-compose.yml` ファイルを使用して、Docker 環境を構築します。HTTPS 用のポート 443 を開放し、SSL 証明書と秘密鍵を適切な場所にマウントします。

#### docker-compose.yml を編集

```plaintext
ports:
      - "443:443" # HTTPS用のポートを追加
volumes:
- ./path/to/your/nginx.crt:/path/to/your/nginx.crt # SSL証明書をマウント
- ./path/to/your/nginx.key:/path/to/your/nginx.key # 秘密鍵をマウント
```

####default.conf を編集

```plaintext
listen 443 ssl;
ssl_certificate /path/to/your/ssl/nginx.crt;　 # SSL証明書へのパスを更新
ssl_certificate_key /path/to/your/ssl/nginx.key;::　 # 秘密鍵へのパスを更新
```

設定を更新した後、以下のコマンドを使用して Docker コンテナを再起動してください。これにより、新しい設定が適用されます。

```bash
docker-compose down
docker-compose up -d
```

####ディレクトリの作成とストレージ設定

- プロジェクトを始める前に、以下のディレクトリを作成してください。これにより、ファイルの保存場所が正しく設定されます。
- 店舗画像の保存と QR コードを保存するために以下のディレクトリを作成してください。PHP コンテナ内で実行します。

```bash
mkdir -p /path/to/your/project/storage/app/public/images
mkdir -p /path/to/your/project/storage/app/public/qr_codes
php artisan storage:link
```

次に、これらのディレクトリに適切なパーミッションを設定してください。これにより、アプリケーションがファイルを適切に読み書きできるようになります。

```bash
chmod -R 775 /var/www/storage/app/public/images
chmod -R 775 /var/www/storage/app/public/qr_codes
chown -R www-data:www-data /var/www/storage/app/public
```
- シーダー用の画像を配置する
  - シーダーで使用する画像は `src/public/shops_img` にあります。これらの画像を `src/storage/app/public/images` にコピーしてください。これにより、アプリケーションがこれらの画像を正しく参照できるようになります。


これらのコマンドは、アプリケーションが画像や QR コードを保存するためのディレクトリに適切なアクセス権を設定するために必要です。`www-data` はウェブサーバーのユーザーですが、使用しているサーバーによっては異なるユーザー名になる場合があるため、環境に合わせて適宜変更してください。

6. アプリケーションキーの生成

```bash
php artisan key:generate
```

6.データベースのマイグレーション

```bash
php artisan migrate
```

7.データベースシーダーの実行

- 全てのシーダーを実行する前に、`Areas` テーブルと `Genres` テーブルのシーダーを先に実行する必要があります。これにより、依存関係が正しく処理されます。

```bash
php artisan db:seed --class=AreasTableSeeder
php artisan db:seed --class=GenresTableSeeder
```

- 上記のシーダーが完了した後、残りのシーダーを実行します。

```bash
php artisan db:seed
```

####リマインダーメールを送るために必要な Cron ジョブの設定手順

#####Laravel スケジューラを利用するためには、Cron ジョブの設定だけでなく、Laravel のスケジューラを適切に設定する必要があります。以下に、Laravel のスケジューラ設定の完全な手順を示します。

- Laravel スケジューラの設定

Laravel のスケジューラを使用するには、app/Console/Kernel.php ファイル内でスケジュールされたタスクを定義する必要があります。以下は、Kernel.php ファイルにスケジュールを設定する方法の例です。

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

この設定では、send:reservation-reminder コマンドが毎日 7 時に実行され、その実行結果が storage/logs/reservation_reminder.log に記録されます。appendOutputTo メソッドを使用して、コマンドの出力をログファイルに追記するように設定しています。時間は指定したい時刻に変更ください。

### メール設定

プロジェクトでは開発環境でのメール送信のテストに Mailtrap を使用しています。

![Mailtrapのホームページ](mailtrap_home.png)

**1.アカウント作成***
`https://mailtrap.io/` のサイトからサインアップタブをクリックし、アカウント作成します。

![サインアップ画面](image-1.png)
![サインアップ画面](image.png)

**2. Start testingのクリック**
赤枠の部分のStart testingをクリックします。もしくは、左サイドバーで「Email Testing」＞「Inboxes」をクリックします。

![Start testingボタン](image-2.png)

**3. Inbox画面への移動**
Inbox画面に移動したら、Integrationsのセレクトボックスをクリックしてください。

![Inbox画面](image-3.png)

**4. フレームワークの選択**
使用しているフレームワーク等を選びます。Laravel8を使っていたのでLaravel 8.xを選びました。

![フレームワーク選択画面](image-4.png)

**5. Laravelの設定**
laravel 8.xを選択したら、Laravel8の設定をクリックします。

![Laravel設定画面](image-5.png)

**6. .env設定のコピー**
Laravelを選択した場合は以下のように.envに貼り付ける用のコードが出ますので、コピーします。

![env設定コード](image-6.png)

**7. .envファイルへの設定追加**
下の設定を `.env` ファイルに追加してください。これにより、開発中のメール送信を安全にテストすることができます。

- `MAIL_MAILER`: メールドライバー（例: smtp, sendmail）
- `MAIL_HOST`: メールサーバーのホスト名
- `MAIL_PORT`: メールサーバーのポート番号
- `MAIL_USERNAME`: メールサーバーのユーザー名
- `MAIL_PASSWORD`: メールサーバーのパスワード
- `MAIL_ENCRYPTION`: メール送信の暗号化方式（例: tls, ssl）
- `MAIL_FROM_NAME`: メール送信時の差出人名（環境変数 `APP_NAME` を使用する場合もあり）

```plaintext
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username # Mailtrapのユーザー名をここに入力
MAIL_PASSWORD=your_mailtrap_password # Mailtrapのパスワードをここに入力
MAIL_ENCRYPTION=tls
MAIL_FROM_NAME="${APP_NAME}" # アプリケーション名を使用する場合
MAIL_LOG_CHANNEL=stack
```

この設定を適用後、アプリケーションからのメールは Mailtrap の仮想 SMTP サーバーを通じて送信され、実際には配信されずに Mailtrap のダッシュボードで確認することができます。

### Stripe 設定

プロジェクトで決済処理を行うために Stripe を使用します。Stripe の API キーを設定することで、安全に決済を処理できます。以下の手順に従って設定を行ってください。

1. Stripe にアクセスし、アカウントを作成またはログインします。
2. ダッシュボードから API キーを取得します。必要なキーは以下の二つです：
   - `STRIPE_KEY`: Stripe の公開可能キー（Public key）
   - `STRIPE_SECRET`: Stripe の秘密キー（Secret key）
3. `.env.example` ファイルを `.env` としてコピーします。
4. コピーした `.env` ファイルを開き、以下の環境変数を更新します：

```plaintext
   STRIPE_KEY=ここに公開可能キーを貼り付ける
   STRIPE_SECRET=ここに秘密キーを貼り付ける
```

### 注意事項

- **セキュリティのため、Stripe の API キーを公開リポジトリにアップロードしないでください。**
- これらのキーはあなたのアカウントと直接関連しており、不正利用される可能性があります。
- 環境変数を使用することで、開発環境と本番環境で異なるキーを簡単に切り替えることができます。

この設定により、アプリケーションは Stripe を介して安全に決済処理を行うことができます。

### URL

- **開発環境:** [https://localhost/](https://localhost/)
- **phpMyAdmin:** [http://localhost:8080/](http://localhost:8080/)

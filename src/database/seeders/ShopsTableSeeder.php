<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use Carbon\Carbon;

class ShopsTableSeeder extends Seeder
{
    public function run()
    {
        $shopNames = [
            ['仙人', '東京都', '寿司', '料理長厳選の食材から作る寿司を用いたコースをぜひお楽しみください。食材・味・価格、お客様の満足度を徹底的に追及したお店です。特別な日のお食事、ビジネス接待まで気軽に使用することができます。','storage/images/sushi.jpg', '10:00', '22:00'],

            ['牛助','大阪府','焼肉','焼肉業界で20年間経験を積み、肉を熟知したマスターによる実力派焼肉店。長年の実績とお付き合いをもとに、なかなか食べられない希少部位も仕入れております。また、ゆったりとくつろげる空間はお仕事終わりの一杯や女子会にぴったりです。','storage/images/yakiniku.jpg', '11:00', '23:00'],

            ['戦慄','福岡県','居酒屋','気軽に立ち寄れる昔懐かしの大衆居酒屋です。キンキンに冷えたビールを、なんと199円で。鳥かわ煮込み串は販売総数100000本突破の名物料理です。仕事帰りに是非御来店ください。','storage/images/izakaya.jpg', '17:00', '23:50'],

            ['ルーク','東京都','イタリアン','都心にひっそりとたたずむ、古民家を改築した落ち着いた空間です。イタリアで修業を重ねたシェフによるモダンなイタリア料理とソムリエセレクトによる厳選ワインとのペアリングが好評です。ゆっくりと上質な時間をお楽しみください。','storage/images/italian.jpg', '11:00', '22:00'],

            ['志摩屋','福岡県','ラーメン','ラーメン屋とは思えない店内にはカウンター席はもちろん、個室も用意してあります。ラーメンはこってり系・あっさり系ともに揃っています。その他豊富な一品料理やアルコールも用意しており、居酒屋としても利用できます。ぜひご来店をお待ちしております。','storage/images/ramen.jpg', '11:00', '22:00'],

            ['香','東京都','焼肉','大小さまざまなお部屋をご用意してます。デートや接待、記念日や誕生日など特別な日にご利用ください。皆様のご来店をお待ちしております。','storage/images/yakiniku.jpg', '11:30', '22:00'],

            ['JJ','大阪府','イタリアン','イタリア製ピザ窯芳ばしく焼き上げた極薄のミラノピッツァや厳選されたワインをお楽しみいただけます。女子会や男子会、記念日やお誕生日会にもオススメです。','storage/images/italian.jpg', '11:00', '22:00'],
            
            ['らーめん極み','東京都','ラーメン','一杯、一杯心を込めて職人が作っております。味付けは少し濃いめです。 食べやすく最後の一滴まで美味しく飲めると好評です。','storage/images/ramen.jpg', '11:00', '23:00'],

            ['鳥雨','大阪府','居酒屋','素材の旨味を存分に引き出す為に、塩焼を中心としたお店です。比内地鶏を中心に、厳選素材を職人が備長炭で豪快に焼き上げます。清潔な内装に包まれた大人の隠れ家で贅沢で優雅な時間をお過ごし下さい。','storage/images/izakaya.jpg', '17:30', '22:00'],

            ['築地色合','東京都','寿司','鮨好きの方の為の鮨屋として、迫力ある大きさの握りを1貫ずつ提供致します。','storage/images/sushi.jpg', '10:00', '21:00'],

            ['晴海','大阪府','焼肉','毎年チャンピオン牛を買い付け、仙台市長から表彰されるほどの上質な仕入れをする精肉店オーナーの本当に美味しい国産牛を食べてもらいたいという思いから誕生したお店です。','storage/images/yakiniku.jpg', '11:00', '22:00'],

            ['三子','福岡県','焼肉','最高級の美味しいお肉で日々の疲れを軽減していただければと贅沢にサーロインを盛り込んだ御膳をご用意しております。','storage/images/yakiniku.jpg', '11:30', '22:00'],

            ['八戒','東京都','居酒屋','当店自慢の鍋や焼き鳥などお好きなだけ堪能できる食べ放題プランをご用意しております。飲み放題は2時間と3時間がございます。','storage/images/izakaya.jpg', '17:00', '23:30'],

            ['THE TOOL','福岡県','イタリアン','非日常的な空間で日頃の疲れを癒し、ゆったりとした上質な時間を過ごせる大人の為のレストラン&バーです。','storage/images/italian.jpg', '11:30', '22:00'],

            ['木船','大阪府','寿司','毎日店主自ら市場等に出向き、厳選した魚介類が、お鮨をはじめとした繊細な料理に仕立てられます。また、選りすぐりの種類豊富なドリンクもご用意しております。','storage/images/sushi.jpg', '11:00', '22:00']
        ];

        $areaIds = Area::pluck('id', 'area_name');
        $genreIds = Genre::pluck('id', 'genre_name');

        foreach ($shopNames as $index => $shopName) {
            if (count($shopName) !== 7) {
                echo "インデックス {$index} の要素数: " . count($shopName) . "\n";
                throw new \Exception("インデックス {$index} のショップデータが不完全です。");
            }

            $manager = User::factory()->shopManager($index + 1)->create();  // ショップマネージャーを作成
            $shop = Shop::create([
                'user_id' => $manager->id,
                'shop_name' => $shopName[0],
                'description' => $shopName[3],
                'image' => $shopName[4],
                'open_time' => $shopName[5],
                'close_time' => $shopName[6],
            ]);

            // 地域とジャンルの関連付け
            DB::table('shops_areas')->insert([
                'shop_id' => $shop->id,
                'area_id' => $areaIds[$shopName[1]]
            ]);

            DB::table('shops_genres')->insert([
                'shop_id' => $shop->id,
                'genre_id' => $genreIds[$shopName[2]]
            ]);
        }
    }
}
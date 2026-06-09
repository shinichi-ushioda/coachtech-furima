<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ItemsTableSeeder extends Seeder
{
    public function run()
    {
         $logoAssets = [
            'COACHTECHヘッダーロゴ.png' => 'https://amazonaws.com',
            'ハートロゴ_デフォルト.png' => 'https://amazonaws.com',
            'ハートロゴ_ピンク.png'      => 'https://amazonaws.com',
            'ふきだしロゴ.png'         => 'https://amazonaws.com',
        ];

        foreach ($logoAssets as $saveName => $s3LogoUrl) {
            try {
                $logoContents = file_get_contents($s3LogoUrl);
                if ($logoContents !== false) {
                    //storage/app/public 直下にロゴ画像を自動保存
                    Storage::disk('public')->put($saveName, $logoContents);
                }
            } catch (\Exception $e) {
                // 通信エラー時のスキップ用
            }
        }

        $items = [
            [
                'name' => '腕時計',
                'price' => 15000,
                'brand_name' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'condition_id' => 1,
                'user_id' => 1,
            ],
            [
                'name' => 'HDD',
                'price' => 5000,
                'brand_name' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'condition_id' => 2,
                'user_id' => 3,
            ],
            [
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand_name' => 'なし',
                'description' => '新鮮な玉ねぎ3束のセット',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'condition_id' => 3,
                'user_id' => 2,
            ],
            [
                'name' => '革靴',
                'price' => 4000,
                'brand_name' => '',
                'description' => 'クラシックなデザインの革靴',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'condition_id' => 4,
                'user_id' => 1,
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000,
                'brand_name' => '',
                'description' => '高性能なノートパソコン',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'condition_id' => 1,
                'user_id' => 3,
            ],
            [
                'name' => 'マイク',
                'price' => 8000,
                'brand_name' => 'なし',
                'description' => '高音質なレコーディング用マイク',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'condition_id' => 2,
                'user_id' => 3,
            ],
            [
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand_name' => '',
                'description' => 'おしゃれなショルダーバッグ',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'condition_id' => 3,
                'user_id' => 2,
            ],
            [
                'name' => 'タンブラー',
                'price' => 500,
                'brand_name' => 'なし',
                'description' => '使いやすいタンブラー',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'condition_id' => 4,
                'user_id' => 3,
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand_name' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'condition_id' => 1,
                'user_id' => 1,
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500,
                'brand_name' => '',
                'description' => '便利なメイクアップセット',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'condition_id' => 2,
                'user_id' => 2,

            ],
        ];

        foreach ($items as $itemData) {
            $s3Url = $itemData['img_url'];
            
            $fileName = basename($s3Url);
            
            $fileName = rawurldecode($fileName);

            try {
                $imageContents = file_get_contents($s3Url);

                if ($imageContents !== false) {
                    Storage::disk('public')->put($fileName, $imageContents);
                }
            } catch (\Exception $e) {
                $fileName = 'sample.jpg'; 
            }

            DB::table('items')->insert([
                'name'         => $itemData['name'],
                'price'        => $itemData['price'],
                'brand_name'   => $itemData['brand_name'],
                'description'  => $itemData['description'],
                'img_url'      => $fileName, 
                'condition_id' => $itemData['condition_id'],
                'user_id'      => $itemData['user_id'],
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        foreach (range(1, 10) as $itemId) {
            $categoryIds = collect(range(1, 14))
                ->shuffle()
                ->take(rand(1, 3))
                ->values()
                ->all();

            foreach ($categoryIds as $categoryId) {
                DB::table('item_category')->insert([
                    'item_id' => $itemId,
                    'category_id' => $categoryId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

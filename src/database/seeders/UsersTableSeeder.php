<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
   {
        DB::table('users')->insert([
            [
                'name' => '山田太郎',
                'email' => 'taro@example.com',
                'password' => Hash::make('taro_pass123'),
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区1-2-3',
                'building' => '渋谷ビル101',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '佐藤花子',
                'email' => 'hanako@example.com',
                'password' => Hash::make('hanako_pass456'),
                'postal_code' => '234-5678',
                'address' => '大阪府大阪市4-5-6',
                'building' => '大阪マンション202',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '鈴木一郎',
                'email' => 'ichiro@example.com',
                'password' => Hash::make('ichiro_pass789'),
                'postal_code' => '345-6789',
                'address' => '愛知県名古屋市7-8-9',
                'building' => '名古屋タワー303',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

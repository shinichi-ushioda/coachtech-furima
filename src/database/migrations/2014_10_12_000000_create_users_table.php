<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('postal_code', 8)->nullable(); //最初の新規登録画面で郵便番号登録欄がないのでNULLABLEにしておく
            $table->string('address')->nullable(); //最初の新規登録画面で郵便番号登録欄がないのでNULLABLEにしておく
            $table->string('building')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

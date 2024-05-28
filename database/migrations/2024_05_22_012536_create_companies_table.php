<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 255)->default('Default Company Name'); // デフォルト値を設定
            $table->string('street_address', 255);
            $table->string('representative_name', 255);
            $table->timestamps();
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->string('name')->default('Default Name')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('street_address')->nullable(); // 住所
            $table->string('representative_name')->nullable(); // 代表者の名前
            $table->timestamps();
        });

        Schema::dropIfExists('companies');
    }
};
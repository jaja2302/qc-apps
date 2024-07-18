<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlokMatchesTable extends Migration
{
    public function up()
    {
        Schema::create('blok_matches', function (Blueprint $table) {
            $table->string('blok_asli')->primary();
            $table->string('blok')->notNull();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blok_matches');
    }
}

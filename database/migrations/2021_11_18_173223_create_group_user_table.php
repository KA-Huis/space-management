<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupUserTable extends Migration
{
    public function up(): void
    {
        Schema::create('group_user', function (Blueprint $table) {
            $table->bigInteger('group_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_user');
    }
}

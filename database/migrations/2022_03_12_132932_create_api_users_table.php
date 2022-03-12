<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiUsersTable extends Migration
{
    public function up(): void
    {
        Schema::create('api_users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('email');
            $table->string('password');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_users');
    }
}

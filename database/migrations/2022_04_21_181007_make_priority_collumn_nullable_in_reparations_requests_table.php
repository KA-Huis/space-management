<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up()
    {
        Schema::table('reparation_requests', function (Blueprint $table) {
            $table->integer('priority')->unsigned()->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('reparations_requests', function (Blueprint $table) {
            $table->integer('priority')->unsigned()->change();
        });
    }
};

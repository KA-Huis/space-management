<?php

declare(strict_types=1);

use App\Models\AuthorizedUser;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('reparation_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('title');
            $table->text('description');
            $table->foreignIdFor(AuthorizedUser::class, 'reporter_id');
            $table->integer('priority')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reparation_requests');
    }
};

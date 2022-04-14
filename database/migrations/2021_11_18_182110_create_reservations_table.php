<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Group;
use App\Models\Space;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->foreignIdFor(Space::class);
            $table->foreignIdFor(User::class, 'created_by_user_id');
            $table->foreignIdFor(Group::class)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};

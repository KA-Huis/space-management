<?php

declare(strict_types=1);

use App\Models\AuthorizedUser;
use App\Models\Reservation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('reservation_participant', function (Blueprint $table) {
            $table->foreignIdFor(Reservation::class);
            $table->foreignIdFor(AuthorizedUser::class);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_participant');
    }
};

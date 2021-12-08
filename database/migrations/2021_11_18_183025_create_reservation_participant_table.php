<?php

declare(strict_types=1);

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationParticipantTable extends Migration
{
    public function up(): void
    {
        Schema::create('reservation_participant', function (Blueprint $table) {
            $table->foreignIdFor(Reservation::class);
            $table->foreignIdFor(User::class);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_participant');
    }
}

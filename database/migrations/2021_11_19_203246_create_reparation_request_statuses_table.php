<?php

declare(strict_types=1);

use App\Models\ReparationRequest;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReparationRequestStatusesTable extends Migration
{
    public function up(): void
    {
        Schema::create('reparation_request_statuses', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignIdFor(ReparationRequest::class);
            $table->integer('status')->unsigned();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reparation_request_statuses');
    }
}

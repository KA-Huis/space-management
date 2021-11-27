<?php

use App\Models\ReparationRequest;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReparationRequestMaterialsTable extends Migration
{
    public function up(): void
    {
        Schema::create('reparation_request_materials', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('name');
            $table->boolean('is_mandatory');
            $table->foreignIdFor(ReparationRequest::class);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reparation_request_materials');
    }
}
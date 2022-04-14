<?php

declare(strict_types=1);

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** @var Application */
    private Application $application;

    public function __construct()
    {
        $this->application = app();
    }

    public function up(): void
    {
        if (!$this->shouldRun()) {
            return;
        }

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('payload');
            $table->integer('last_activity')->index();
        });

    }

    public function down(): void
    {
        if (!$this->shouldRun()) {
            return;
        }

        Schema::dropIfExists('sessions');
    }

    private function shouldRun(): bool
    {
        return $this->application->environment('acceptance');
    }
};

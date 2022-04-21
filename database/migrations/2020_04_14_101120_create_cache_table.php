<?php

declare(strict_types=1);

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
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

        Schema::create('cache', function ($table) {
            $table->string('key')->unique();
            $table->text('value');
            $table->integer('expiration');
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
        return $this->application->environment(['acceptance']);
    }
};

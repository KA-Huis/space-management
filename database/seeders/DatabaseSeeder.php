<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\ACL\Contracts\ACLService;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(ACLService $ACLService): void
    {
        $ACLService->synchroniseRolesAndPermissions();

        $this->call(UserDatabaseSeeder::class);
    }
}

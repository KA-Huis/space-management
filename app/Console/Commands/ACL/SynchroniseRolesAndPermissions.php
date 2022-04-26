<?php

declare(strict_types=1);

namespace App\Console\Commands\ACL;

use App\ACL\Contracts\ACLService;
use Illuminate\Console\Command;

class SynchroniseRolesAndPermissions extends Command
{
    protected $signature = 'system:acl:synchronise-roles-and-permissions';

    protected $description = 'Synchronise the roles and permissions with the database';

    public function handle(ACLService $ACLService): int
    {
        $ACLService->synchroniseRolesAndPermissions();

        $this->info('Finished synchronising the roles and permissions');

        return self::SUCCESS;
    }
}

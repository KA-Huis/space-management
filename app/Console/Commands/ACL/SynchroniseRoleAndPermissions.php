<?php

declare(strict_types=1);

namespace App\Console\Commands\ACL;

use App\ACL\Contracts\ACLService;
use App\Authentication\Contracts\GuardService;
use App\Authentication\Exceptions\InvalidGuard;
use Illuminate\Console\Command;

class SynchroniseRoleAndPermissions extends Command
{
    protected $signature = 'system:acl:synchronise-roles-and-permissions {guard=web}';

    protected $description = 'Synchronise the roles and permissions with the database';

    public function handle(ACLService $ACLService, GuardService $guardService): int
    {
        try {
            $guard = $guardService->getByName($this->argument('guard'));
        } catch (InvalidGuard $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $ACLService->synchroniseRolesAndPermissions($guard);

        $this->info('Finished synchronising the roles and permissions');

        return self::SUCCESS;
    }
}

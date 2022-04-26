<?php

declare(strict_types=1);

namespace Tests\Feature\ACL;

use App\ACL\Contracts\ACLService;
use App\ACL\Contracts\RolesProvider;
use App\ACL\Roles\RoleCollection;
use App\ACL\Roles\RoleInterface;
use App\Authentication\Guards\WebGuard;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ACLServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testSynchroniseRolesAndPermissions(): void
    {
        // Given
        $this->app->singleton(RolesProvider::class, function (Container $app) {
            return new class() implements RolesProvider {
                public function resolve(): RoleCollection
                {
                    return new RoleCollection([
                        new class() implements RoleInterface {
                            public function getName(): string
                            {
                               return 'test';
                            }

                            public function getPermissions(): Collection
                            {
                                return new Collection([
                                    'view_user',
                                    'create_user',
                                ]);
                            }
                        }
                    ]);
                }
            };
        });

        /** @var ACLService $service */
        $service = $this->app->get(ACLService::class);

        // When
        $service->synchroniseRolesAndPermissions(new WebGuard());

        // Then
        $role = Role::findByName('test');

        self::assertInstanceOf(Role::class, $role);

        self::assertTrue($role->hasAllPermissions([
            'view_user',
            'create_user',
        ]));
    }
}

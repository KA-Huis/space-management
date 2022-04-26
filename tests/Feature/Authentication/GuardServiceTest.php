<?php

declare(strict_types=1);

namespace Tests\Feature\Authentication;

use App\Authentication\Contracts\GuardService;
use App\Authentication\Exceptions\InvalidGuard;
use App\Authentication\Guards\GuardInterface;
use App\Authentication\Guards\RestApiGuard;
use App\Authentication\Guards\WebGuard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuardServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testItCanGuardOneByName(): void
    {
        // Given
        /** @var GuardService $service */
        $service = $this->app->get(GuardService::class);

        // When
        $guard = $service->getByName((new WebGuard())->getName());

        // Then
        self::assertInstanceOf(GuardInterface::class, $guard);
        self::assertEquals((new WebGuard())->getName(), $guard->getName());
    }

    public function testItThrowsInvalidGuardExceptionIfNoGuardIsFound(): void
    {
        // Expects
        $this->expectException(InvalidGuard::class);

        // Given
        /** @var GuardService $service */
        $service = $this->app->get(GuardService::class);

        // When
        $service->getByName('something_random');
    }

    public function itCanDetermineIfAGuardExistsByNameDataProvider(): array
    {
        return [
            [
                'name'     => (new WebGuard())->getName(),
                'response' => true,
            ],
            [
                'name'     => (new RestApiGuard())->getName(),
                'response' => true,
            ],
            [
                'name'     => 'random',
                'response' => false,
            ],
        ];
    }

    /**
     * @dataProvider itCanDetermineIfAGuardExistsByNameDataProvider
     */
    public function testItCanDetermineIfAGuardExistsByName(string $guardName, bool $expectedResponse): void
    {
        // Given
        /** @var GuardService $service */
        $service = $this->app->get(GuardService::class);

        // When
        $response = $service->existsByName($guardName);

        // Then
        self::assertEquals($expectedResponse, $response);
    }
}

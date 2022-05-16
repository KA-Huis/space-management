<?php

declare(strict_types=1);

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Policies\UserPolicy;
use Mockery;
use PHPUnit\Framework\TestCase;

class UserPolicyTest extends TestCase
{
    /**
     * This tests the `viewAny` action.
     *
     * @testdox This verifies the happy flow when someone is allowed to view all reparation requests.
     */
    public function testViewAny(): void
    {
        // Given
        $policy = new UserPolicy();

        $user = Mockery::mock(User::class);

        // When
        $response = $policy->viewAny($user);

        // Then
        self::assertTrue($response);
    }

    /**
     * This tests the `view` action.
     *
     * @testdox This verifies the happy flow when someone is allowed to view a reparation request.
     */
    public function testView(): void
    {
        // Given
        $policy = new UserPolicy();

        $user = Mockery::mock(User::class);
        $userSubject = Mockery::mock(User::class);

        // When
        $response = $policy->view($user, $userSubject);

        // Then
        self::assertTrue($response);
    }

    /**
     * This tests the `viewPrivateProfile` action.
     *
     * @testdox This verifies the happy flow when someone is allowed to view a reparation request.
     */
    public function testViewPrivateProfile(): void
    {
        // Given
        $policy = new UserPolicy();

        $user = Mockery::mock(User::class);

        // When
        $response = $policy->viewPrivateProfile($user, $user);

        // Then
        self::assertTrue($response);
    }

    /**
     * This tests the `viewPrivateProfile` action.
     *
     * @testdox This verifies the flow when someone is not allowed to view a private user profile, because it's not its
     * own profile.
     */
    public function testViewPrivateProfileWhenSubjectIsDifferentThanAuthenticatedUser(): void
    {
        // Given
        $policy = new UserPolicy();

        $user = Mockery::mock(User::class);
        $user->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn(1)
            ->once();

        $userSubject = Mockery::mock(User::class);
        $userSubject->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn(2)
            ->once();

        // When
        $response = $policy->viewPrivateProfile($user, $userSubject);

        // Then
        self::assertFalse($response);
    }

    /**
     * This tests the `create` action.
     *
     * @testdox This verifies the happy flow when someone is allowed to create a reparation request.
     */
    public function testCreate(): void
    {
        // Given
        $policy = new UserPolicy();

        $user = Mockery::mock(User::class);

        // When
        $response = $policy->create($user);

        // Then
        self::assertTrue($response);
    }

    /**
     * This tests the `update` action.
     *
     * @testdox This verifies the happy flow when someone is allowed to update a reparation request.
     */
    public function testUpdate(): void
    {
        // Given
        $policy = new UserPolicy();

        $user = Mockery::mock(User::class);
        $userSubject = Mockery::mock(User::class);

        // When
        $response = $policy->update($user, $userSubject);

        // Then
        self::assertTrue($response);
    }

    /**
     * This tests the `delete` action.
     *
     * @testdox This verifies the happy flow when someone is allowed to view a reparation request.
     */
    public function testDelete(): void
    {
        // Given
        $policy = new UserPolicy();

        $user = Mockery::mock(User::class);
        $userSubject = Mockery::mock(User::class);

        // When
        $response = $policy->delete($user, $userSubject);

        // Then
        self::assertTrue($response);
    }

    /**
     * This tests the `forceDelete` action.
     *
     * @testdox This verifies the happy flow when someone is allowed to view a reparation request.
     */
    public function testForceDelete(): void
    {
        // Given
        $policy = new UserPolicy();

        $user = Mockery::mock(User::class);
        $userSubject = Mockery::mock(User::class);

        // When
        $response = $policy->forceDelete($user, $userSubject);

        // Then
        self::assertTrue($response);
    }
}

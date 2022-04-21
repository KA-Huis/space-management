<?php

declare(strict_types=1);

namespace Tests\Unit\Policies;

use App\Models\ReparationRequest;
use App\Models\User;
use App\Policies\ReparationRequestPolicy;
use Mockery;
use PHPUnit\Framework\TestCase;

class ReparationRequestPolicyTest extends TestCase
{
    /**
     * This tests the `viewAny` action.
     *
     * @testdox This verifies the happy flow when someone is allowed to view all reparation requests.
     */
    public function testViewAny(): void
    {
        // Given
        $policy = new ReparationRequestPolicy();

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
        $policy = new ReparationRequestPolicy();

        $user = Mockery::mock(User::class);
        $reparationRequest = Mockery::mock(ReparationRequest::class);

        // When
        $response = $policy->view($user, $reparationRequest);

        // Then
        self::assertTrue($response);
    }

    /**
     * This tests the `create` action.
     *
     * @testdox This verifies the happy flow when someone is allowed to create a reparation request.
     */
    public function testCreate(): void
    {
        // Given
        $policy = new ReparationRequestPolicy();

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
        $policy = new ReparationRequestPolicy();

        $user = Mockery::mock(User::class);
        $reparationRequest = Mockery::mock(ReparationRequest::class);

        // When
        $response = $policy->update($user, $reparationRequest);

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
        $policy = new ReparationRequestPolicy();

        $user = Mockery::mock(User::class);
        $reparationRequest = Mockery::mock(ReparationRequest::class);

        // When
        $response = $policy->delete($user, $reparationRequest);

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
        $policy = new ReparationRequestPolicy();

        $user = Mockery::mock(User::class);
        $reparationRequest = Mockery::mock(ReparationRequest::class);

        // When
        $response = $policy->forceDelete($user, $reparationRequest);

        // Then
        self::assertTrue($response);
    }
}

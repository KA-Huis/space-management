<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\AuthorizedUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function testConfirmPasswordScreenCanBeRendered()
    {
        $this->markTestSkipped();

        $user = AuthorizedUser::factory()->create();

        $response = $this->actingAs($user)->get('/confirm-password');

        $response->assertStatus(200);
    }

    public function testPasswordCanBeConfirmed()
    {
        $this->markTestSkipped();

        $user = AuthorizedUser::factory()->create();

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function testPasswordIsNotConfirmedWithInvalidPassword()
    {
        $this->markTestSkipped();

        $user = AuthorizedUser::factory()->create();

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    }
}

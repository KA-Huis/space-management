<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @group feature
 * @group models
 */
class UserTest extends TestCase
{
    use DatabaseTransactions;

    public function getFullNameDataProvider(): array
    {
        return [
            'only firstname' => [
                'attributes' => [
                    'first_name' => 'John',
                    'last_name' => null,
                ],
                'full_name_result' => 'John',
            ],
            'only lastname' => [
                'attributes' => [
                    'first_name' => null,
                    'last_name' => 'Doe',
                ],
                'full_name_result' => 'Doe',
            ],
            'both firstname and lastname' => [
                'attributes' => [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                ],
                'full_name_result' => 'John Doe',
            ],
            'null values for firstname and lastname' => [
                'attributes' => [
                    'first_name' => null,
                    'last_name' => null,
                ],
                'full_name_result' => '',
            ],
            'empty values for firstname and lastname' => [
                'attributes' => [
                    'first_name' => '',
                    'last_name' => '    ',
                ],
                'full_name_result' => '',
            ],
        ];
    }

    /** @dataProvider getFullNameDataProvider */
    public function testGetFullName(array $attributes, string $fullName)
    {
        // Given
        $user = new User($attributes);

        // When
        $generatedFullName = $user->getFullName();

        // Then
        self::assertEquals($fullName, $generatedFullName);
    }
}

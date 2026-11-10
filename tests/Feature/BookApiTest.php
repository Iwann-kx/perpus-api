<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_a_book(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/books', [
            'isbn' => '978-623-1234-56-7',
            'title' => 'Belajar Laravel',
            'author' => 'Edwin Hap',
            'year' => 2026,
            'stock' => 10,
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.year', 2026);

        $this->assertDatabaseHas('books', [
            'isbn' => '978-623-1234-56-7',
            'year' => 2026,
        ]);
    }
}

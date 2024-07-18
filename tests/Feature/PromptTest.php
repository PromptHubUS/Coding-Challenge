<?php

use App\Models\User;

test('test prompt creation', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post('/prompts', [
            'question' => 'What is the capital of France?',
            'modifier' => 'Translate to French.',
        ])
        ->assertStatus(200);

    $response->assertOk();
});
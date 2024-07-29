<?php

use App\Models\User;

test('prompt index page is loaded ', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/prompt');

    $response->assertOk();
});

test('prompt index page has question and modifier', function () {
    $user = User::factory()->create();

    $user->questions()->create([
        'content' => 'What is the capital of France?',
    ]);
    $user->modifiers()->create([
        'content' => 'In a friendly tone',
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/prompt');

    $response->assertOk();


    $response->assertInertia(fn ($page) => $page
        ->has('lastQuestion')
    );

    $response->assertInertia(fn ($page) => $page
        ->where('lastQuestion', 'What is the capital of France?')
    );

    $response->assertInertia(fn ($page) => $page
        ->has('lastModifier')
    );

    $response->assertInertia(fn ($page) => $page
        ->where('lastModifier', 'In a friendly tone')
    );

});


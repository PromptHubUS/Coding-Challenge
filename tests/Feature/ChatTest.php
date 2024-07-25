<?php

use App\Models\User;
use App\Models\Conversation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

test('chat page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/chat');

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Chat')
        ->has('conversations'));
});

test('view conversation page is displayed', function () {
    $user = User::factory()->create();
    $conversation = Conversation::factory()->create(['user_id' => $user->id]);

    $response = $this
        ->actingAs($user)
        ->get('/chat/' . $conversation->id);

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Chat')
        ->has('current_chat')
        ->has('conversations'));
});

test('view conversation redirects to chat page if conversation does not exist', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/chat/9999');

    $response->assertRedirect(route('chat'));
});

test('conversation can be sent and displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post('/chat', [
            'message' => 'Test message',
        ]);

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Chat')
        ->has('current_chat'));
});

test('conversation can be sent to an existing chat and displayed', function () {
    $user = User::factory()->create();
    $conversation = Conversation::factory()->create(['user_id' => $user->id]);

    $response = $this
        ->actingAs($user)
        ->post('/chat/' . $conversation->id, [
            'message' => 'Test message',
        ]);

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Chat')
        ->has('current_chat'));
});

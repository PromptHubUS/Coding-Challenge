<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use function Pest\Laravel\actingAs;

//This will remove any previous data in the database
uses(RefreshDatabase::class);

it('processes the prompt and modifier then stores the response', function () {
    $user = User::factory()->create();

    // Mock the LLMService, we dont want to spend money on the API just for tests
    $llmServiceMock = Mockery::mock('App\Services\LLMService');
    $llmServiceMock->shouldReceive('processInput')
        ->once()
        ->with('Test prompt', 'Test modifier')
        ->andReturn([
            'intermediate_result' => 'Intermediate result',
            'final_result' => 'Final result',
        ]);

    $this->app->instance('App\Services\LLMService', $llmServiceMock);

    // Make a POST request to the process route and make sure it redirects to the dashboard
    actingAs($user)->post('/prompt/process', [
        'prompt' => 'Test prompt',
        'modifier' => 'Test modifier',
    ])
    ->assertStatus(302)
    ->assertRedirect(route('dashboard'));

    // Assert the prompt response is stored in the database
    $this->assertDatabaseHas('prompt_responses', [
        'user_id' => $user->id,
        'prompt' => 'Test prompt',
        'modifier' => 'Test modifier',
        'intermediate_result' => 'Intermediate result',
        'final_result' => 'Final result',
    ]);
});

it('handles errors gracefully', function () {
    $user = User::factory()->create();

    // Mock the LLMService to throw an exception
    $llmServiceMock = Mockery::mock('App\Services\LLMService');
    $llmServiceMock->shouldReceive('processInput')
        ->once()
        ->with('Test prompt', 'Test modifier')
        ->andThrow(new Exception('Processing error'));
    $this->app->instance('App\Services\LLMService', $llmServiceMock);

    actingAs($user)->post('/prompt/process', [
        'prompt' => 'Test prompt',
        'modifier' => 'Test modifier',
    ])
    ->assertStatus(302)
    ->assertRedirect(route('dashboard', ['error' => 'An error occurred while processing the input.']));
      
    // Assert the prompt response is NOT stored in the database
    $this->assertDatabaseMissing('prompt_responses', [
        'user_id' => $user->id,
        'prompt' => 'Test prompt',
        'modifier' => 'Test modifier',
    ]);
});

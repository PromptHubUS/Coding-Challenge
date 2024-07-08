<?php

use App\Models\User;
use App\Models\AiModel;
use App\Models\PromptResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

it('processes the prompt and modifier then stores the response', function () {
    // Create a user
    $user = User::factory()->create();

    // Create an AI model
    $model = AiModel::factory()->create(['name' => 'gpt-3.5-turbo']);

    // Mock the LLMService
    $llmServiceMock = Mockery::mock('App\Services\LLMService');
    $llmServiceMock->shouldReceive('processInput')
        ->once()
        ->with($model->name, 'Test prompt', 'Test modifier')
        ->andReturn([
            'intermediate_result' => 'Intermediate result',
            'final_result' => 'Final result',
        ]);
    $this->app->instance('App\Services\LLMService', $llmServiceMock);

    // Act as the created user and make a post request to the process route
    actingAs($user)->post('/prompt/process', [
        'model_id' => $model->id,
        'prompt' => 'Test prompt',
        'modifier' => 'Test modifier',
    ])->assertStatus(302) // Assert the redirection status
      ->assertRedirect(route('dashboard')); // Assert the redirection to the dashboard

    // Assert the prompt response is stored in the database
    $this->assertDatabaseHas('prompt_responses', [
        'user_id' => $user->id,
        'model_id' => $model->id,
        'prompt' => 'Test prompt',
        'modifier' => 'Test modifier',
        'intermediate_result' => 'Intermediate result',
        'final_result' => 'Final result',
    ]);
});

it('handles errors gracefully', function () {
    // Create a user
    $user = User::factory()->create();

    // Create an AI model
    $model = AiModel::factory()->create(['name' => 'gpt-3.5-turbo']);

    // Mock the LLMService to throw an exception
    $llmServiceMock = Mockery::mock('App\Services\LLMService');
    $llmServiceMock->shouldReceive('processInput')
        ->once()
        ->with($model->name, 'Test prompt', 'Test modifier')
        ->andThrow(new Exception('Processing error'));
    $this->app->instance('App\Services\LLMService', $llmServiceMock);

    // Act as the created user and make a post request to the process route
    actingAs($user)->post('/prompt/process', [
        'model_id' => $model->id,
        'prompt' => 'Test prompt',
        'modifier' => 'Test modifier',
    ])->assertStatus(302) // Assert the redirection status
      ->assertRedirect(route('dashboard')); // Assert the redirection to the dashboard

    // Assert the prompt response is NOT stored in the database
    $this->assertDatabaseMissing('prompt_responses', [
        'user_id' => $user->id,
        'model_id' => $model->id,
        'prompt' => 'Test prompt',
        'modifier' => 'Test modifier',
    ]);
});

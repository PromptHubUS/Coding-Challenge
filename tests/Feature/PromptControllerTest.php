<?php

use App\Http\Enums\PromptModelEnum;
use App\Http\Resources\Prompt\PromptResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Chat\CreateResponse;

uses(RefreshDatabase::class);

test('prompt page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/prompts');

    $response->assertOk();
});

test('create prompt chain', function () {
    OpenAI::fake([
        CreateResponse::fake([
            'choices' => [
                [
                    'message' => [
                        'content' => 'Hi! How are you?',
                    ],
                ],
            ],
        ]),
    ]);

    $user = User::factory()->create();

    $request = [
        'step'       => 1,
        'content'    => 'Hello!',
        'model_name' => PromptModelEnum::GPT_3_5_TURBO->value,
    ];

    $response = $this
        ->actingAs($user)
        ->post('/prompts/chain', $request);

    $response->assertCreated();

    $prompt = $response->original;

    $resource = PromptResource::make($prompt);

    $response->assertJson($resource->response()->getData(true));

    $this->assertDatabaseHas('prompts', [
        'content'    => $prompt->content,
        'output'     => $prompt->output,
        'model_name' => $prompt->model_name,
    ]);
});

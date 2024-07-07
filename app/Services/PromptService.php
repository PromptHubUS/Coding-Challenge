<?php

namespace App\Services;

use App\Models\Prompt\Prompt;
use OpenAI\Laravel\Facades\OpenAI;

class PromptService
{
    /**
     * @return mixed
     */
    public function getPrompts()
    {
        return Prompt::query()
                     ->where('user_id', auth()->id())
                     ->with(['chains'])
                     ->latest()
                     ->get();
    }

    /**
     * @param array $payload
     * @return Prompt
     */
    public function createChain(array $payload): Prompt
    {
        $step    = $payload['step'];
        $content = $payload['content'];
        $model   = $payload['model_name'];

        $prompt = Prompt::query()->findOrNew($payload['prompt_id'] ?? null);

        $prompt->user()->associate(auth()->user());

        if ($step === 1) {
            if ($prompt->exists) {
                $prompt->chains()->delete();
            }
        } else {
            $chain = $prompt->chains()->latest()->first();

            $content = $chain->output . PHP_EOL . $content;
        }

        $result = OpenAI::chat()->create([
            'model'    => $model,
            'messages' => [
                ['role' => 'user', 'content' => $content],
            ],
        ]);

        $prompt->fill(
            $data = [
                'content'    => $content,
                'output'     => $result->choices[0]->message->content,
                'model_name' => $model,
            ]
        );

        $prompt->save();
        $prompt->chains()->create($data);

        return $prompt;
    }
}

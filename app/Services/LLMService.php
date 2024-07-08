<?php

namespace App\Services;

use OpenAI\Client;

class LLMService {
    protected $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function processInput($model, $prompt, $modifier) {
        if (empty($prompt) || empty($modifier)) {
            throw new \InvalidArgumentException('Prompt and modifier must be provided and cannot be empty.');
        }

        $promptResponse = $this->processPrompt($model, $prompt);

        $intermediateResult = $promptResponse['choices'][0]['message']['content'] ?? '';

        $modifierResponse = $this->processPrompt($model, $modifier . ' ' . $intermediateResult);

        $finalResult = $modifierResponse['choices'][0]['message']['content'] ?? '';

        return [
            'intermediate_result' => $intermediateResult,
            'final_result' => $finalResult,
        ];
    }

    private function processPrompt($model, $prompt) {
        return $this->client->chat()->create([
            'model' => $model,
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);
    }
}

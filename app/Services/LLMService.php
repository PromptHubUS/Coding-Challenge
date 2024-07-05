<?php

namespace App\Services;

use OpenAI\Client;

class LLMService {
    protected $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function processInput($prompt, $modifier) {
        if (empty($prompt) || empty($modifier)) {
            throw new \InvalidArgumentException('Prompt and modifier must be provided and cannot be empty.');
        }

        $promptResponse = $this->processPrompt($prompt);

        $intermediateResult = $promptResponse['choices'][0]['message']['content'] ?? '';

        $modifierResponse = $this->processPrompt($modifier . ' ' . $intermediateResult);

        $finalResult = $modifierResponse['choices'][0]['message']['content'] ?? '';

        return [
            'intermediate_result' => $intermediateResult,
            'final_result' => $finalResult,
        ];
    }

    private function processPrompt($prompt) {
        return $this->client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);
    }
}

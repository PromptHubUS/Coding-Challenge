<?php

namespace App\Services\AI\Adapters;

use OpenAI\Laravel\Facades\OpenAI;
use App\Models\Conversation;
use App\Services\AI\Interfaces\AIClientInterface;

class OpenAIClientAdapter implements AIClientInterface
{
  public function sendMessage(Conversation $conversation, string $message): string
  {
    // Retrieve and format the messages from the conversation
    $messages = $conversation->messages()->get()->map(function ($msg) {
        return ['role' => $msg->role, 'content' => $msg->message];
    })->toArray();

    // Add the new user message to the messages array
    $messages[] = ['role' => 'user', 'content' => $message];

    // Call the OpenAI API with the complete message history
    $result = OpenAI::chat()->create([
        'model' => 'gpt-3.5-turbo',
        'messages' => $messages,
    ]);

    return $result->choices[0]->message->content;
  }
}
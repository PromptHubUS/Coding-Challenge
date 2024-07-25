<?php

namespace App\Services\AI;

use App\Models\Conversation;
use App\Models\User;
use App\Services\AI\Interfaces\AIClientInterface;

class ChatService
{
  protected $client;

  public function __construct(AIClientInterface $client)
  {
    $this->client = $client;
  }

  public function handleConversation(string $message, User $user, Conversation $conversation): void
  {
    // Send the user message to the AI client
    $response = $this->client->sendMessage($conversation, $message);

    // Save the user message
    $conversation->messages()->create(['role' => 'user', 'message' => $message]);

    // Save the AI response
    $conversation->messages()->create(['role' => 'assistant', 'message' => $response]);

    $conversation->load('messages');
  }
}
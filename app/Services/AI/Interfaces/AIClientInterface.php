<?php

namespace App\Services\AI\Interfaces;

use App\Models\Conversation;

interface AIClientInterface
{
  public function sendMessage(Conversation $conversation, string $message): string;
}
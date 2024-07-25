<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatRequest;
use App\Models\Conversation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\AI\Adapters\OpenAIClientAdapter;
use App\Services\AI\ChatService;

class ChatController extends Controller
{
    protected $chatClient;

    public function __construct()
    {
        $this->chatClient = new ChatService(new OpenAIClientAdapter());
    }

    public function chat(Request $request): Response
    {
        $user = $request->user();

        return Inertia::render('Chat', [
            'conversations' => Conversation::where(['user_id' => $user->id])->orderBy('created_at', 'desc')->get(['id', 'question']),
        ]);
    }

    public function view(Request $request): RedirectResponse|Response
    {
        $user = $request->user();
        $conversation = Conversation::with('messages')->find($request->route('id'));

        if (!$conversation) {
            return Redirect::route('chat');
        }

        return Inertia::render('Chat', [
            'current_chat' => $conversation,
            'conversations' => Conversation::where(['user_id' => $user->id])->orderBy('created_at', 'desc')->get(['id', 'question']),
        ]);
    }

    public function send(ChatRequest $request): Response
    {
        $prompt = $request->validated();
        $chatId = $request->route('chatId');
        $user = $request->user();

        if ($chatId) {
            $conversation = Conversation::with('messages')->find($chatId);
        } else {
            $conversation = Conversation::create(['question' => $prompt['message'], 'user_id' => $user->id]);
        }
        
        $this->chatClient->handleConversation($prompt['message'], $user, $conversation);

        return Inertia::render('Chat', [
            'current_chat' => $conversation,
        ]);
    }
}

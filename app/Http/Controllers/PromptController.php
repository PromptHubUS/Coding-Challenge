<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PromptController extends Controller
{
    /**
     * Store a new prompt and send it to ChatGPT.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'modifier' => 'required|string',
        ]);

        $question = $request->input('question');
        $modifier = $request->input('modifier');

        // Combine the modifier and question to form the prompt
        $prompt = "Please answer the following question '$question', applying the following rule to the response: '$modifier'";

        // Send the prompt to ChatGPT using the chat/completions endpoint
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt,
                ]
            ],
            'max_tokens' => 150,
            'temperature' => 0.7,
        ]);

        // Return the response from ChatGPT
        return response()->json($response->json());
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PromptController extends Controller
{
    /**
     * Store a new prompt and stream the response from ChatGPT.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'modifier' => 'required|string',
        ]);

        $question = $request->input('question');
        $modifier = $request->input('modifier');

        // Define the prompt and modifier clearly
        $prompt = "Please answer the following question. After providing your answer, apply the following rule to the response: '$modifier'.\n\nQuestion: $question";

        // Create a Guzzle client
        $client = new Client();

        // Create a streamed response
        return new StreamedResponse(function () use ($client, $prompt) {
            try {
                // Send the prompt to ChatGPT using the chat/completions endpoint
                $response = $client->request('POST', 'https://api.openai.com/v1/chat/completions', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'model' => 'gpt-3.5-turbo',
                        'messages' => [
                            [
                                'role' => 'user',
                                'content' => $prompt,
                            ]
                        ],
                        'max_tokens' => 150,
                        'temperature' => 0.7,
                        'stream' => true,
                    ],
                    'stream' => true,
                ]);

                // Capture the response body
                $body = $response->getBody();

                // Output the response in chunks
                while (!$body->eof()) {
                    echo $body->read(1024); // Read and output 1024 bytes at a time
                    ob_flush(); // Flush the output buffer
                    flush();    // Flush the system output buffer
                }

            } catch (RequestException $e) {
                // Handle exception
                echo json_encode([
                    'error' => $e->getMessage(),
                ]);
            }
        }, 200, [
            'Content-Type' => 'text/plain', // Use text/plain for simple streaming
        ]);
    }
}

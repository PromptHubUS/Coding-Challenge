<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $prompt = "Please answer the following question. After providing your answer, apply the following rule to the response: '$modifier'.\n\nQuestion: $question";

        $client = new Client();

       DB::table('user_prompts')->insert([
            'question' => $question,
            'modifier' => $modifier,
            'user_id' => auth()->id(),
            'prompt_output' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $promptId = DB::getPdo()->lastInsertId();

        return new StreamedResponse(function () use ($client, $prompt, $question, $modifier, $promptId) {
            try {
                $response = $client->request('POST', 'https://api.openai.com/v1/chat/completions', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'model' => 'gpt-3.5-turbo',
                        'messages' => [
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'max_tokens' => 150,
                        'temperature' => 0.7,
                        'stream' => true,
                    ],
                    'stream' => true,
                ]);

                $body = $response->getBody();
                $currentAnswer = '';
                $buffer = '';

                while (!$body->eof()) {
                    $chunk = $body->read(1024);
                    $buffer .= $chunk;

                    $lines = explode("\n", $buffer);
                    $buffer = array_pop($lines);

                    foreach ($lines as $line) {
                        if (strpos($line, 'data: ') === 0) {
                            $json = json_decode(substr($line, 6), true);
                            if (isset($json['choices'][0]['delta']['content'])) {
                                $formattedChunk = $json['choices'][0]['delta']['content'];
                                $currentAnswer .= $formattedChunk;
                                echo "data: " . json_encode(['content' => $currentAnswer]) . "\n\n";
                                ob_flush();
                                flush();
                            }
                        }
                    }
                }

                DB::table('user_prompts')
                    ->where('id', $promptId)
                    ->update(['prompt_output' => $currentAnswer, 'updated_at' => now()]);

            } catch (RequestException $e) {
                echo "data: " . json_encode(['error' => $e->getMessage()]) . "\n\n";
                ob_flush();
                flush();
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
        ]);
    }

    /**
     * Display the last prompt output.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $prompt = DB::table('user_prompts')
            ->where('user_id', auth()->id())
            ->orderBy('id', 'desc')
            ->first();

        return response()->json($prompt);
    }
}

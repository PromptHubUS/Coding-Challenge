<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Question;
use App\Models\Modifier;

class PromptController extends Controller
{
    const MODEL = 'gpt-4o-mini';

    public function index(Request $request): Response
    {
        $user = Auth::user();
        $lastQuestion = $user->questions()->latest()->first();
        $lastModifier = $user->modifiers()->latest()->first();

        return Inertia::render('Prompt/Prompt', [
            'lastQuestion' => $lastQuestion->content ?? '',
            'lastModifier' => $lastModifier->content ?? '',
        ]);
    }

    public function step1(Request $request): JsonResponse
    {
        $question = $request->input('question');

        $user = Auth::user();

        $existingQuestion = Question::where('content', $question)
        ->where('user_id', $user->id)
        ->first();

        if (!$existingQuestion) {
            $savedQuestion = Question::create([
                'content' => $question,
                'user_id' => $user->id,
            ]);
        }

        // Step 1: Get initial response from LLM
        $response = OpenAI::chat()->create([
            'model' => self::MODEL,
            'messages' => [
                [
                    'role' => 'user', 
                    'content' => $question,
                ]
            ],
        ]);

        $output = $response->choices[0]->message->content;

        return response()->json(['output' => $output]);
    }

    public function step2(Request $request): JsonResponse
    {
        $modifier = $request->input('modifier');
        $step1Output = $request->input('step1Output');

        $user = Auth::user();

        $existingModifier = Modifier::where('content', $modifier)
        ->where('user_id', $user->id)
        ->first();

        if (!$existingModifier) {
            $savedModifier = Modifier::create([
                'content' => $modifier,
                'user_id' => $user->id,
            ]);
        }

        // Step 2: Modify the initial response
        $response = OpenAI::chat()->create([
            'model' => self::MODEL,
            'messages' => [
                [
                    "role" => "system", 
                    "content" => 
                    "User will provide you 2 texts. Modifier and step1Output. 
                    A modifier that will transform the step1Output in some way defined by the user _(ex. translate to another language or rephrase to use a particular tone or accent)_."
                ],
                [
                    'role' => 'user', 
                    'content' => "Modifier: " . $modifier . "\n\nstep1Output: " . $step1Output,
                ],
            ],
        ]);

        $finalOutput = $response->choices[0]->message->content;

        return response()->json(['output' => $finalOutput]);
    }
}



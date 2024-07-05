<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LLMService;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

use App\Models\PromptResponse;

class PromptController extends Controller {
    protected $llmService;

    public function __construct(LLMService $llmService) {
        $this->llmService = $llmService;
    }

    public function process(Request $request) {
        $validated = $request->validate([
            'prompt' => 'required|string',
            'modifier' => 'required|string',
        ]);

        $prompt = $validated['prompt'];
        $modifier = $validated['modifier'];

        Log::info('Processing input:', ['prompt' => $prompt, 'modifier' => $modifier]);

        try {
            $response = $this->llmService->processInput($prompt, $modifier);

            PromptResponse::create([
                'user_id' => $request->user()->id,
                'prompt' => $prompt,
                'modifier' => $modifier,
                'intermediate_result' => $response['intermediate_result'],
                'final_result' => $response['final_result'],
            ]);

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            Log::error('Error processing input:', ['error' => $e->getMessage()]);
             // The Dashboard cant actually handle the error right now, but would be nice to have
           return redirect()->route('dashboard', ['error' => 'An error occurred while processing the input.']);
        }
    }
}


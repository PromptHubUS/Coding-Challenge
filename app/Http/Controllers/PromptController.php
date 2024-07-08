<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\LLMService;
use Illuminate\Support\Facades\Log;
use App\Models\AiModel;
use App\Http\Requests\ProcessPromptRequest;

use App\Models\PromptResponse;

class PromptController extends Controller {
    protected $llmService;

    public function __construct(LLMService $llmService) {
        $this->llmService = $llmService;
    }

    public function showForm(Request $request): Response {
        $models = AiModel::all();
        return Inertia::render('Dashboard', [
            'models' => $models,
            'promptResponses' => $request->user()->promptResponses()->with('model')->get(),
        ]);
    }

    public function process(ProcessPromptRequest $request) {
        $validated = $request->validated();
        $model = AiModel::find($validated['model_id']);
        $prompt = $validated['prompt'];
        $modifier = $validated['modifier'];

        Log::info('Processing input:', ['prompt' => $prompt, 'modifier' => $modifier]);

        try {
            $response = $this->llmService->processInput($model->name, $prompt, $modifier);

            PromptResponse::create([
                'user_id' => $request->user()->id,
                'model_id' => $model->id,
                'prompt' => $prompt,
                'modifier' => $modifier,
                'intermediate_result' => $response['intermediate_result'],
                'final_result' => $response['final_result'],
            ]);

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            Log::error('Error processing input:', ['error' => $e->getMessage()]);
            // The Dashboard cant actually handle the error right now, but would be nice to have
            return redirect()->route('dashboard')->withErrors('error', 'An error occurred while processing the input.');
        }
    }
}


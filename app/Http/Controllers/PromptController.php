<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use OpenAI\Laravel\Facades\OpenAI;

class PromptController extends Controller
{
    public function store(Request $request, OpenAI $open_ai)
    {
        try{
            $validated = $request->validate([
                'question' => 'required|string',
                'modifier' => 'required|string',
            ]);
    
            $user = auth()->user();
            $prompt = $user->prompts()->create($validated);
    
            // First Prompt
            $firstResponse = $open_ai->completions()->create([
                'model' => 'text-davinci-002',
                'prompt' => $validated['question'],
            ]);
    
            $prompt->first_prompt_output = $firstResponse['choices'][0]['text'];
    
            // Second Prompt
            $secondResponse = $open_ai->completions()->create([
                'model' => 'text-davinci-002',
                'prompt' => $prompt->first_prompt_output . ' ' . $validated['modifier'],
            ]);
    
            $prompt->final_output = $secondResponse['choices'][0]['text'];
            $prompt->save();
    
            return Inertia::render('Prompt/Index', ['prompt' => $prompt, 'status' => session('status')]);
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return Inertia::render('Prompt/Index', ['errors' => "An error occurred.", 'status' => session('status')]);
        }
    }

    public function index()
    {
        return Inertia::render('Prompt/Index', [
            'prompts' => auth()->user()->prompts()->latest()->get(),
            'status' => session('status'),
        ]);
    }
}

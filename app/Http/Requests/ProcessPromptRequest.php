<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessPromptRequest extends FormRequest
{
    public function authorize()
    {
        // Ensure the user is logged in
        return auth()->check();
    }

    public function rules()
    {
        return [
            'model_id' => 'required|exists:ai_models,id',
            'prompt' => 'required|string',
            'modifier' => 'required|string',
        ];
    }
}

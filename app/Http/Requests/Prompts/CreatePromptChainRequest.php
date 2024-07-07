<?php

namespace App\Http\Requests\Prompts;

use App\Http\Enums\PromptModelEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreatePromptChainRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'step'      => [
                'required',
                'integer',
                Rule::in([1, 2]),
            ],
            'model_name'     => [
                'required',
                Rule::in(PromptModelEnum::cases()),
            ],
            'content'   => [
                'required',
                'string',
            ],
            'prompt_id' => [
                'nullable',
                'integer',
                'exists:prompts,id',
            ],
        ];
    }
}

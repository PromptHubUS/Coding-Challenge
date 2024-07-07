<?php

namespace App\Http\Resources\Prompt;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromptChainResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'content'    => $this->content,
            'output'     => $this->output,
            'model_name' => $this->model_name,
            'prompt_id'  => $this->prompt_id,
        ];
    }
}

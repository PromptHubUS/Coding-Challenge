<?php

namespace App\Models\Prompt;

use App\Http\Enums\PromptModelEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromptChain extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'prompt_id',
        'content',
        'output',
        'model_name',
    ];

    protected $casts = [
        'model_name' => PromptModelEnum::class,
    ];

    /**
     * @return BelongsTo
     */
    public function prompt(): BelongsTo
    {
        return $this->belongsTo(Prompt::class);
    }
}

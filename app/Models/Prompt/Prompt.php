<?php

namespace App\Models\Prompt;

use App\Http\Enums\PromptModelEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prompt extends Model
{
    use HasFactory;

    protected $fillable = [
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
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany
     */
    public function chains(): HasMany
    {
        return $this->hasMany(PromptChain::class);
    }
}

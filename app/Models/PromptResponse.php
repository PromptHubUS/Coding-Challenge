<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromptResponse extends Model
{
    protected $fillable = [
        'user_id',
        'model_id',
        'prompt',
        'modifier',
        'intermediate_result',
        'final_result',
    ];

    /**
     * This is not needed for the assesment but is a nice to have
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function model()
    {
        return $this->belongsTo(AiModel::class);
    }
}

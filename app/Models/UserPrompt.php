<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPrompt extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'question', 'modifier', 'prompt_output'];

    /**
     * Get the user that owns the prompt.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

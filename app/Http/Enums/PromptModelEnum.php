<?php

namespace App\Http\Enums;

enum PromptModelEnum: string
{
    case GPT_3_5_TURBO = 'gpt-3.5-turbo';
    case GPT_4_TURBO   = 'gpt-4-turbo';
}

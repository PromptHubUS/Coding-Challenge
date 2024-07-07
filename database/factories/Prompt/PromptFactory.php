<?php

namespace Database\Factories\Prompt;

use App\Http\Enums\PromptModelEnum;
use App\Models\Prompt\Prompt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;


class PromptFactory extends Factory
{
    protected $model = Prompt::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content'    => $this->faker->text(),
            'output'     => $this->faker->text(),
            'model_name' => PromptModelEnum::GPT_3_5_TURBO,
            'user_id'    => User::factory()->create(),
        ];
    }
}

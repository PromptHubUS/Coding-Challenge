<?php 

namespace Database\Factories;

use App\Models\AiModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class AiModelFactory extends Factory
{
    protected $model = AiModel::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}

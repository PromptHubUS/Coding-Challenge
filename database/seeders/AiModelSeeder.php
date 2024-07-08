<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AiModel;

class AiModelSeeder extends Seeder
{
    public function run()
    {
        $models = [
            ['name' => 'gpt-3.5-turbo'],
            ['name' => 'gpt-4'],
            ['name' => 'davinci'],// This one will make it easy to test the error response since is already deprecated
            // Add other models as necessary
        ];

        foreach ($models as $model) {
            AiModel::create($model);
        }
    }
}

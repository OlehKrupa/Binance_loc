<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    protected $model = News::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraphs(3, true),
            'image' => $this->faker->imageUrl(),
            'source' => $this->faker->word(),
            'published_at' => $this->faker->dateTimeThisYear,
        ];
    }
}

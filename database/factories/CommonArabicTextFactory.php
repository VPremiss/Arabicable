<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VPremiss\Arabicable\Models\CommonArabicText;

class CommonArabicTextFactory extends Factory
{
    public function modelName()
    {
        return config('arabicable.common_arabic_text_model', CommonArabicText::class);
    }

    public function definition()
    {
        return [
            'content' => fake('ar_SA')->unique()->sentence(rand(1, 4)),
        ];
    }
}

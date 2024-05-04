<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VPremiss\Crafty\Facades\CraftyPackage;

class CommonArabicTextFactory extends Factory
{
    public function modelName()
    {
        return CraftyPackage::getConfiguration('arabicable.common_arabic_text.model');
    }

    public function definition()
    {
        return [
            'content' => fake('ar_SA')->unique()->sentence(rand(1, 4)),
        ];
    }
}

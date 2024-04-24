<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VPremiss\Arabicable\ArabicableServiceProvider;
use VPremiss\Crafty\Facades\CraftyPackage;

class CommonArabicTextFactory extends Factory
{
    public function modelName()
    {
        return CraftyPackage::validatedConfig('arabicable.common_arabic_text.model', ArabicableServiceProvider::class);
    }

    public function definition()
    {
        return [
            'content' => fake('ar_SA')->unique()->sentence(rand(1, 4)),
        ];
    }
}

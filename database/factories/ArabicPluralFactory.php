<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VPremiss\Crafty\Facades\CraftyPackage;

class ArabicPluralFactory extends Factory
{
    public function modelName()
    {
        return CraftyPackage::getConfiguration('arabicable.arabic_plural.model');
    }

    public function definition()
    {
        return [
            'singular' => fake('ar_SA')->word(),
            'plural' => fake('ar_SA')->word(),
        ];
    }
}

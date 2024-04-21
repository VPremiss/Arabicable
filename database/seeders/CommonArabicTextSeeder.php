<?php

declare(strict_types = 1);

namespace VPremiss\Arabicable\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use VPremiss\Arabicable\Models\CommonArabicText;

class CommonArabicTextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $commonArabicTextModel = config('arabicable.common_arabic_text.model', CommonArabicText::class);

        $commonArabicTextModel::factory()->create(['content' => 'رسول الله صلى الله عليه وسلم']);
    }
}

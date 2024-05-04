<?php

declare(strict_types=1);

use Database\Seeders\CommonArabicTextSeeder;
use VPremiss\Arabicable\Models\CommonArabicText;

use function Pest\Laravel\seed;

it('can seed the database with common arabic texts', function () {
    seed(CommonArabicTextSeeder::class);

    expect(CommonArabicText::count())->toBeGreaterThan(0);
});

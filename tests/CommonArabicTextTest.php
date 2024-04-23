<?php

declare(strict_types=1);

use VPremiss\Arabicable\Models\CommonArabicText;

it('will have the database seeded with common arabic texts', function () {
    expect(CommonArabicText::count())->toBeGreaterThan(0);
});

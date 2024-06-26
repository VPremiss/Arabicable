<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Models;

use VPremiss\Arabicable\Traits\Arabicable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use VPremiss\Arabicable\Enums\CommonArabicTextType;
use VPremiss\Crafty\Facades\CraftyPackage;

/** 
 * VPremiss\Arabicable\Models\CommonArabicText
 * 
 * @property string $content
 */
class CommonArabicText extends Model
{
    use HasFactory;
    use Arabicable;

    protected $fillable = [
        'type',
        'content',
    ];

    protected function casts(): array
    {
        return [
            'type' => CommonArabicTextType::class,
        ];
    }

    protected static function newFactory()
    {
        return CraftyPackage::getConfiguration('arabicable.common_arabic_text.factory')::new();
    }
}

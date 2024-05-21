<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Models;

use VPremiss\Arabicable\Traits\Arabicable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use VPremiss\Crafty\Facades\CraftyPackage;

/** 
 * VPremiss\Arabicable\Models\ArabicPlural
 * 
 * @property string $content
 */
class ArabicPlural extends Model
{
    use HasFactory;
    use Arabicable;

    protected $fillable = [
        'singular',
        'plural',
    ];

    protected static function newFactory()
    {
        return CraftyPackage::getConfiguration('arabicable.arabic_plural.factory')::new();
    }
}

<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Models;

use VPremiss\Arabicable\Traits\Arabicable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Searchable;
use VPremiss\Arabicable\ArabicableServiceProvider;
use VPremiss\Crafty\Facades\CraftyPackage;

class CommonArabicText extends Model
{
    use HasFactory, Searchable;
    use Arabicable;

    protected $fillable = ['content'];

    protected static function newFactory()
    {
        return CraftyPackage::validatedConfig('arabicable.common_arabic_text.factory', ArabicableServiceProvider::class)::new();
    }

    #[SearchUsingFullText(['content_searchable'])]
    public function toSearchableArray(): array
    {
        return [
            'content' => $this->{ar_searchable('content')},
        ];
    }
}

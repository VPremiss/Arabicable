<?php

namespace VPremiss\Arabicable\Models;

use VPremiss\Arabicable\Traits\Arabicable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Searchable;

class CommonArabicText extends Model
{
    use HasFactory, Searchable;
    use Arabicable;

    protected $fillable = ['content'];

    #[SearchUsingFullText(['content_searchable'])]
    public function toSearchableArray(): array
    {
        return [
            'content' => $this->{ar_searchable('content')},
        ];
    }
}

<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Enums;

use VPremiss\Crafty\Utilities\Enumerified\Traits\Enumerified;

enum CommonArabicTextType: string
{
    use Enumerified;

    case Separator = 'separator';
    case Verb = 'verb';
    case Noun = 'noun';
    case Name = 'name';
    case Sentence = 'sentence';
}

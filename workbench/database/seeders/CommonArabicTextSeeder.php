<?php

declare(strict_types=1);

namespace Workbench\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use VPremiss\Arabicable\Enums\ArabicLinguisticConcept;
use VPremiss\Arabicable\Enums\CommonArabicTextType;
use VPremiss\Arabicable\Facades\Arabic;
use VPremiss\Arabicable\Facades\ArabicFilter;
use VPremiss\Crafty\Facades\Crafty;

class CommonArabicTextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ? ======================================================================================
        // ?   The idea is to have these checked for during search as separators to be put aside,
        // ?   while the left letters are removed, and we're left with sentences for exact
        // ?   -search; after the whole query was exact-searched for in the beginning.
        // ? =======================================================================
        // ?   Notice how we consider only the words that would not have meaning or goal to be
        // ?   searched for. And if you're not sure about the word, feel free to contribute
        // ?   with the word or sentence commented out.
        // ? =========================================

        $commonArabicTexts = [

            // * ===========
            // * Separators
            // * =========

            ['type' => CommonArabicTextType::Separator, 'content' => 'أن'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'إن'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'إني'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'وإن'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'فإن'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'فإنك'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'أنه'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'أنها'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'كأن'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'وكأن'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'كأنه'],

            ['type' => CommonArabicTextType::Separator, 'content' => 'ما'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'وما'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'لا'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'ولا'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'فلا'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'نعم'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'ليس'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'ليست'],

            ['type' => CommonArabicTextType::Separator, 'content' => 'إذا'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'فإذا'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'لما'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'فلما'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'ثم'],

            ['type' => CommonArabicTextType::Separator, 'content' => 'غير'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'سوى'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'إلا'],

            ['type' => CommonArabicTextType::Separator, 'content' => 'نحن'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'أنا'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'وأنا'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'أنت'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'هو'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'وهو'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'هي'],

            ['type' => CommonArabicTextType::Separator, 'content' => 'ذا'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'هذا'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'هذه'],

            ['type' => CommonArabicTextType::Separator, 'content' => 'لم'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'ولم'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'فلم'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'فإن لم'],

            ['type' => CommonArabicTextType::Separator, 'content' => 'قد'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'وقد'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'فقد'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'لقد'],

            ['type' => CommonArabicTextType::Separator, 'content' => 'كلما'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'حين'],

            ['type' => CommonArabicTextType::Separator, 'content' => 'حيث'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'عند'],

            ['type' => CommonArabicTextType::Separator, 'content' => 'كان'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'كانت'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'كنت'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'كنا'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'كانوا'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'وكان'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'كان لا'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'وكان لا'],

            ['type' => CommonArabicTextType::Separator, 'content' => 'من'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'منه'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'منهم'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'ومن'],

            ['type' => CommonArabicTextType::Separator, 'content' => 'إلى'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'إليك'],

            ['type' => CommonArabicTextType::Separator, 'content' => 'لك'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'لكم'],

            ['type' => CommonArabicTextType::Separator, 'content' => 'يا'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'ألا'],

            ['type' => CommonArabicTextType::Separator, 'content' => 'مثل'],
            // ['type' => CommonArabicTextType::Separator, 'content' => 'ومثل'],
            // ['type' => CommonArabicTextType::Separator, 'content' => 'كمثل'],

            ['type' => CommonArabicTextType::Separator, 'content' => 'به'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'بها'],

            ['type' => CommonArabicTextType::Separator, 'content' => 'عن'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'عنه'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'عنها'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'عنك'],

            ['type' => CommonArabicTextType::Separator, 'content' => 'ذلك'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'عن ذلك'],

            ['type' => CommonArabicTextType::Separator, 'content' => 'في'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'فيك'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'فيهم'],

            ['type' => CommonArabicTextType::Separator, 'content' => 'على'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'عليه'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'عليها'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'عليهما'],
            ['type' => CommonArabicTextType::Separator, 'content' => 'عليهم'],

            // * ======
            // * Verbs
            // * ====

            ['type' => CommonArabicTextType::Verb, 'content' => 'قول'],
            ['type' => CommonArabicTextType::Verb, 'content' => 'قال'],
            ['type' => CommonArabicTextType::Verb, 'content' => 'فقال'],
            ['type' => CommonArabicTextType::Verb, 'content' => 'يقول'],
            ['type' => CommonArabicTextType::Verb, 'content' => 'قالت'],
            ['type' => CommonArabicTextType::Verb, 'content' => 'قلت'],
            ['type' => CommonArabicTextType::Verb, 'content' => 'فقلت'],
            ['type' => CommonArabicTextType::Verb, 'content' => 'قيل'],
            ['type' => CommonArabicTextType::Verb, 'content' => 'قالوا'],

            ['type' => CommonArabicTextType::Verb, 'content' => 'سمع'],
            ['type' => CommonArabicTextType::Verb, 'content' => 'سمعت'],

            ['type' => CommonArabicTextType::Verb, 'content' => 'عمل'],

            ['type' => CommonArabicTextType::Verb, 'content' => 'فعل'],
            ['type' => CommonArabicTextType::Verb, 'content' => 'فعلت'],

            ['type' => CommonArabicTextType::Verb, 'content' => 'قام'],

            ['type' => CommonArabicTextType::Verb, 'content' => 'دخل'],
            ['type' => CommonArabicTextType::Verb, 'content' => 'دخلت'],

            ['type' => CommonArabicTextType::Verb, 'content' => 'أقبل'],
            ['type' => CommonArabicTextType::Verb, 'content' => 'أتى'],

            ['type' => CommonArabicTextType::Verb, 'content' => 'رأى'],
            ['type' => CommonArabicTextType::Verb, 'content' => 'رأيت'],

            ['type' => CommonArabicTextType::Verb, 'content' => 'جعل'],
            ['type' => CommonArabicTextType::Verb, 'content' => 'جعلت'],

            ['type' => CommonArabicTextType::Verb, 'content' => 'وجد'],
            ['type' => CommonArabicTextType::Verb, 'content' => 'فوجد'],
            ['type' => CommonArabicTextType::Verb, 'content' => 'وجدت'],

            ['type' => CommonArabicTextType::Verb, 'content' => 'أصبح'],

            // ['type' => CommonArabicTextType::Verb, 'content' => 'علم'],
            // ['type' => CommonArabicTextType::Verb, 'content' => 'أعلم'],

            // * ======
            // * Nouns
            // * ====

            // ['type' => CommonArabicTextType::Noun, 'content' => 'الناس'],
            // ['type' => CommonArabicTextType::Noun, 'content' => 'الإنس'],
            // ['type' => CommonArabicTextType::Noun, 'content' => 'الجن'],
            // ['type' => CommonArabicTextType::Noun, 'content' => 'الرجال'],
            // ['type' => CommonArabicTextType::Noun, 'content' => 'الرجل'],
            ['type' => CommonArabicTextType::Noun, 'content' => 'رجل'],
            // ['type' => CommonArabicTextType::Noun, 'content' => 'المرء'],
            // ['type' => CommonArabicTextType::Noun, 'content' => 'النساء'],
            // ['type' => CommonArabicTextType::Noun, 'content' => 'المرأة'],
            ['type' => CommonArabicTextType::Noun, 'content' => 'امرأة'],

            ['type' => CommonArabicTextType::Noun, 'content' => 'شيء'],
            ['type' => CommonArabicTextType::Noun, 'content' => 'شيئا'],
            ['type' => CommonArabicTextType::Noun, 'content' => 'أحد'],
            ['type' => CommonArabicTextType::Noun, 'content' => 'كل'],
            ['type' => CommonArabicTextType::Noun, 'content' => 'كلكم'],
            ['type' => CommonArabicTextType::Noun, 'content' => 'كلهم'],

            ['type' => CommonArabicTextType::Noun, 'content' => 'بن'],
            ['type' => CommonArabicTextType::Noun, 'content' => 'بنت'],
            ['type' => CommonArabicTextType::Noun, 'content' => 'ابن'],
            ['type' => CommonArabicTextType::Noun, 'content' => 'ابنة'],
            ['type' => CommonArabicTextType::Noun, 'content' => 'أخي'],

            // * ======
            // * Names
            // * ====

            ['type' => CommonArabicTextType::Name, 'content' => 'الله'],
            // ['type' => CommonArabicTextType::Name, 'content' => 'جبريل'],
            // ['type' => CommonArabicTextType::Name, 'content' => 'محمد'],
            ['type' => CommonArabicTextType::Name, 'content' => 'عبد الله'],
            ['type' => CommonArabicTextType::Name, 'content' => 'بكر'],
            ['type' => CommonArabicTextType::Name, 'content' => 'عمر'],
            // ['type' => CommonArabicTextType::Name, 'content' => 'عثمان'],
            ['type' => CommonArabicTextType::Name, 'content' => 'علي'],
            // ['type' => CommonArabicTextType::Name, 'content' => 'عائشة'],

            // * ==========
            // * Sentences
            // * ========

            ['type' => CommonArabicTextType::Sentence, 'content' => 'صلى الله عليه وسلم'],
            ['type' => CommonArabicTextType::Sentence, 'content' => 'محمد صلى الله عليه وسلم'],
            ['type' => CommonArabicTextType::Sentence, 'content' => 'النبي صلى الله عليه وسلم'],
            ['type' => CommonArabicTextType::Sentence, 'content' => 'الرسول صلى الله عليه وسلم'],
            ['type' => CommonArabicTextType::Sentence, 'content' => 'رسول الله صلى الله عليه وسلم'],
            ['type' => CommonArabicTextType::Sentence, 'content' => 'نبي الله صلى الله عليه وسلم'],

            ['type' => CommonArabicTextType::Sentence, 'content' => 'عليه السلام'],
            ['type' => CommonArabicTextType::Sentence, 'content' => 'عليه الصلاة والسلام'],
            ['type' => CommonArabicTextType::Sentence, 'content' => 'عليهما السلام'],

            // ['type' => CommonArabicTextType::Sentence, 'content' => 'سبحان الله'],
            // ['type' => CommonArabicTextType::Sentence, 'content' => 'لا إله إلا الله'],
            ['type' => CommonArabicTextType::Sentence, 'content' => 'رسول الله'],
            ['type' => CommonArabicTextType::Sentence, 'content' => 'نبي الله'],

            ['type' => CommonArabicTextType::Sentence, 'content' => 'قال لا'],
            ['type' => CommonArabicTextType::Sentence, 'content' => 'قالت لا'],
            ['type' => CommonArabicTextType::Sentence, 'content' => 'قال نعم'],

            // ['type' => CommonArabicTextType::Sentence, 'content' => 'عند الله'],
            // ['type' => CommonArabicTextType::Sentence, 'content' => 'عند النبي صلى الله عليه وسلم'],
            // ['type' => CommonArabicTextType::Sentence, 'content' => 'عند رسول الله صلى الله عليه وسلم'],

            // ['type' => CommonArabicTextType::Sentence, 'content' => 'يا رسول الله'],
            // ['type' => CommonArabicTextType::Sentence, 'content' => 'يا نبي الله'],

            // ['type' => CommonArabicTextType::Sentence, 'content' => 'في المسجد'],

            ['type' => CommonArabicTextType::Sentence, 'content' => 'رسول الله صلى الله عليه وسلم قال'],
            ['type' => CommonArabicTextType::Sentence, 'content' => 'أن رسول الله صلى الله عليه وسلم قال'],
            ['type' => CommonArabicTextType::Sentence, 'content' => 'قال رسول الله صلى الله عليه وسلم'],
            ['type' => CommonArabicTextType::Sentence, 'content' => 'سمعت الرسول صلى الله عليه وسلم يقول'],
            ['type' => CommonArabicTextType::Sentence, 'content' => 'سمعت رسول صلى الله عليه وسلم يقول'],
            ['type' => CommonArabicTextType::Sentence, 'content' => 'سمعت النبي صلى الله عليه وسلم'],
            ['type' => CommonArabicTextType::Sentence, 'content' => 'سمعت الرسول صلى الله عليه وسلم'],
            ['type' => CommonArabicTextType::Sentence, 'content' => 'سمعت رسول صلى الله عليه وسلم'],

            ['type' => CommonArabicTextType::Sentence, 'content' => 'فلما أصبح'],

        ];

        Crafty::chunkedDatabaseInsertion(
            tableName: 'common_arabic_texts',
            dataArrays: $commonArabicTexts,
            callback: function ($record) {
                $content = $record['content'];

                $record[ar_with_harakat('content')] = ArabicFilter::withHarakat($content);
                $record[ar_searchable('content')] = ArabicFilter::forSearch($content);
                $record['content'] = ArabicFilter::withoutHarakat($content);

                return $record;
            },
        );

        Arabic::clearConceptCache(ArabicLinguisticConcept::CommonTexts);
    }
}

<?php

declare(strict_types = 1);

namespace VPremiss\Arabicable\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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

        $commonArabicText = [
            ['content' => 'صلى الله عليه وسلم'],
            ['content' => 'محمد صلى الله عليه وسلم'],
            ['content' => 'النبي صلى الله عليه وسلم'],
            ['content' => 'الرسول صلى الله عليه وسلم'],
            ['content' => 'رسول الله صلى الله عليه وسلم'],
            ['content' => 'نبي الله صلى الله عليه وسلم'],

            // ['content' => 'سبحان الله'],
            // ['content' => 'لا إله إلا الله'],
            ['content' => 'رسول الله'],
            ['content' => 'نبي الله'],

            ['content' => 'أن'],
            ['content' => 'إن'],
            ['content' => 'إني'],
            ['content' => 'وإن'],
            ['content' => 'فإن'],
            ['content' => 'فإنك'],
            ['content' => 'أنه'],
            ['content' => 'أنها'],
            ['content' => 'كأن'],
            ['content' => 'وكأن'],
            ['content' => 'كأنه'],

            ['content' => 'ما'],
            ['content' => 'وما'],
            ['content' => 'لا'],
            ['content' => 'ولا'],
            ['content' => 'فلا'],
            ['content' => 'قال لا'],
            ['content' => 'قالت لا'],
            ['content' => 'قال نعم'],
            ['content' => 'نعم'],
            ['content' => 'ليس'],
            ['content' => 'ليست'],

            ['content' => 'إذا'],
            ['content' => 'فإذا'],
            ['content' => 'لما'],
            ['content' => 'فلما'],
            ['content' => 'ثم'],

            ['content' => 'غير'],
            ['content' => 'سوى'],
            ['content' => 'إلا'],

            ['content' => 'نحن'],
            ['content' => 'أنا'],
            ['content' => 'وأنا'],
            ['content' => 'أنت'],
            ['content' => 'هو'],
            ['content' => 'وهو'],
            ['content' => 'هي'],

            ['content' => 'ذا'],
            ['content' => 'هذا'],
            ['content' => 'هذه'],

            // ['content' => 'الناس'],
            // ['content' => 'الإنس'],
            // ['content' => 'الجن'],
            // ['content' => 'الرجال'],
            // ['content' => 'الرجل'],
            ['content' => 'رجل'],
            // ['content' => 'المرء'],
            // ['content' => 'النساء'],
            // ['content' => 'المرأة'],
            ['content' => 'امرأة'],

            ['content' => 'أحد'],
            ['content' => 'كل'],
            ['content' => 'كلكم'],
            ['content' => 'كلهم'],

            ['content' => 'بن'],
            ['content' => 'بنت'],
            ['content' => 'ابن'],
            ['content' => 'ابنة'],
            ['content' => 'أخي'],

            ['content' => 'لم'],
            ['content' => 'ولم'],
            ['content' => 'فلم'],
            ['content' => 'فإن لم'],

            ['content' => 'قد'],
            ['content' => 'وقد'],
            ['content' => 'فقد'],
            ['content' => 'لقد'],

            ['content' => 'كلما'],
            ['content' => 'حين'],

            ['content' => 'حيث'],
            ['content' => 'عند'],
            // ['content' => 'عند الله'],
            // ['content' => 'عند النبي صلى الله عليه وسلم'],
            // ['content' => 'عند رسول الله صلى الله عليه وسلم'],

            ['content' => 'كان'],
            ['content' => 'كانت'],
            ['content' => 'كنت'],
            ['content' => 'كنا'],
            ['content' => 'كانوا'],
            ['content' => 'وكان'],
            ['content' => 'كان لا'],
            ['content' => 'وكان لا'],

            ['content' => 'من'],
            ['content' => 'منه'],
            ['content' => 'منهم'],
            ['content' => 'ومن'],

            ['content' => 'إلى'],
            ['content' => 'إليك'],

            ['content' => 'لك'],
            ['content' => 'لكم'],

            ['content' => 'يا'],
            ['content' => 'ألا'],

            ['content' => 'مثل'],
            // ['content' => 'ومثل'],
            // ['content' => 'كمثل'],

            // ['content' => 'يا رسول الله'],
            // ['content' => 'يا نبي الله'],

            ['content' => 'عن'],
            ['content' => 'عنه'],
            ['content' => 'عنها'],
            ['content' => 'عنك'],

            ['content' => 'ذلك'],
            ['content' => 'عن ذلك'],

            ['content' => 'في'],
            // ['content' => 'في المسجد'],
            ['content' => 'فيك'],
            ['content' => 'فيهم'],

            ['content' => 'به'],
            ['content' => 'بها'],

            ['content' => 'قال'],
            ['content' => 'فقال'],
            ['content' => 'يقول'],
            ['content' => 'قالت'],
            ['content' => 'قلت'],
            ['content' => 'فقلت'],
            ['content' => 'قيل'],
            ['content' => 'قالوا'],
            ['content' => 'رسول الله صلى الله عليه وسلم قال'],
            ['content' => 'أن رسول الله صلى الله عليه وسلم قال'],
            ['content' => 'قال رسول الله صلى الله عليه وسلم'],

            ['content' => 'سمعت الرسول صلى الله عليه وسلم يقول'],
            ['content' => 'سمعت رسول صلى الله عليه وسلم يقول'],

            ['content' => 'سمع'],
            ['content' => 'سمعت'],
            ['content' => 'سمعت النبي صلى الله عليه وسلم'],
            ['content' => 'سمعت الرسول صلى الله عليه وسلم'],
            ['content' => 'سمعت رسول صلى الله عليه وسلم'],

            ['content' => 'فعل'],
            ['content' => 'فعلت'],

            ['content' => 'قام'],

            ['content' => 'دخل'],
            ['content' => 'دخلت'],

            ['content' => 'أقبل'],
            ['content' => 'أتى'],

            ['content' => 'رأى'],
            ['content' => 'رأيت'],

            ['content' => 'جعل'],
            ['content' => 'جعلت'],

            ['content' => 'وجد'],
            ['content' => 'فوجد'],
            ['content' => 'وجدت'],

            ['content' => 'أصبح'],
            ['content' => 'فلما أصبح'],

            // ['content' => 'علم'],
            // ['content' => 'أعلم'],

            ['content' => 'على'],
            ['content' => 'عليه'],
            ['content' => 'عليها'],
            ['content' => 'عليهما'],
            ['content' => 'عليهم'],
            ['content' => 'عليه السلام'],
            ['content' => 'عليه الصلاة والسلام'],
            ['content' => 'عليهما السلام'],

            ['content' => 'الله'],
            // ['content' => 'جبريل'],
            // ['content' => 'محمد'],
            ['content' => 'عبد الله'],
            ['content' => 'بكر'],
            ['content' => 'عمر'],
            // ['content' => 'عثمان'],
            ['content' => 'علي'],
            // ['content' => 'عائشة'],
        ];

        Crafty::chunkedDatabaseInsertion(
            tableName: 'common_arabic_texts',
            dataArrays: $commonArabicText,
            callback: function ($record) {
                $content = $record['content'];

                $record[ar_with_harakat('content')] = ArabicFilter::withHarakat($content);
                $record[ar_searchable('content')] = ArabicFilter::forSearch($content);
                $record['content'] = ArabicFilter::withoutHarakat($content);

                return $record;
            },
        );
    }
}

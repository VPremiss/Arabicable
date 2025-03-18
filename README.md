<div align="center">
    بسم الله الرحمن الرحيم
</div>

<div align="left">

# Arabicable

Several effective strategies for managing Arabic text.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vpremiss/arabicable.svg?style=for-the-badge&color=gray)](https://packagist.org/packages/vpremiss/arabicable)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/vpremiss/arabicable/testing-and-analysis.yml?branch=main&label=tests&style=for-the-badge&color=forestgreen)](https://github.com/vpremiss/PACKAGE_LARAVEL_arabicable/actions/workflows/testing-and-analysis.yml?query=branch%3Amain++)
![Codecov](https://img.shields.io/codecov/c/github/vpremiss/arabicable?style=for-the-badge&color=purple)
[![Total Downloads](https://img.shields.io/packagist/dt/vpremiss/arabicable.svg?style=for-the-badge&color=blue)](https://packagist.org/packages/vpremiss/arabicable)


## Description

The primary approach here is to rely on the database to store variances of each Arabic or Indian numeral column. Having their dedicated columns makes indexing and searching efficient; combined with the appropriate choice among all available migration types based on character capactiy. And while utilizing Laravel's Eloquent [observers](https://laravel.com/docs/eloquent#observers), we can process only what's necessary again during updates.

So based on the model's property length requirement, we've added migration blueprint [macros](src/Concerns/HasArabicableMigrationBlueprintMacros.php) for Arabic `string`, `tinyText`, `text`, `mediumText`, and `longText`; plus a `date` one for Indian numerals. Using those `string` or `text`-type ones would generate 2 extra columns for each arabic column (a [configurable](config/arabicable.php) **affix**, that is). And the indian `date` one would generate a `column_indian` to hold that in; where `column` is an example property name.

Finally, take a look at the list of offered methods below (the [API](#API) section) to understand what kind of processing we're doing on the text in order to essentially preserve the `column` (without harakat), the `column_with_harakat`, and the `column_searchable` where each is prepared exactly for that...


## Installation

1. Install the package via [composer](https://getcomposer.org):

   ```bash
   composer require vpremiss/arabicable
   ```

2. Run the package [Artisan](https://laravel.com/docs/artisan) installer using this command:

   ```bash
   php artisan arabicable:install
   ```

>[!NOTE]The config file as well as the migration table will be published automatically.

### Upgrading

1. Backup your current [config](config/arabicable.php), as well as the common-Arabic-text migration and seeder.

2. Republish the package stuff using these Artisan commands:

   ```bash
   php artisan vendor:publish --tag="arabicable-config" --force
   php artisan vendor:publish --tag="arabicable-migrations" --force
   php artisan vendor:publish --tag="arabicable-seeders" --force
   ```

3. Migrate and seed gracefully again on your end; keeping in mind that seeders do change regularily.


## Usage

### Arabicable

Alright, so let's imagine we have `Note`(s) and we want to have their `content` arabicable!

- First, create the [migration](https://laravel.com/docs/migrations) and add an arabicable `column` to it:

  ```php
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;
  // ...
  Schema::create('notes', function (Blueprint $table) {
      $table->id();
      $table->arabicText('content'); // this also creates `content_searchable` and `content_with_harakat` of the same type
      $table->timestamps();
  });
  ```

- Then let's make the model "arabicable", which activates the observer:

  ```php
  use Illuminate\Database\Eloquent\Model;
  use VPremiss\Arabicable\Traits\Arabicable;

  class Note extends Model
  {
      use Arabicable;

      protected $fillable = ['content']; // or you'd guard the property differently
  }
  ```

- Finally, the moment we create a new note and passing it some Arabic content (presumably with harakat), it will process its other columns automatically:

  ```php
  // When spacing_after_punctuation_only is set to `false` in configuration (default)

  $note = Note::create([
      'content' => '"الجَمَاعَةُ مَا وَافَقَ الحَقّ، أَنْتَ الجَمَاعَةُ وَلَو كُنْتَ وَحْدَكْ."',
  ]);

  echo $note->content; // "الجماعة ما وافق الحق ، أنت الجماعة ولو كنت وحدك ."
  echo $note->{ar_with_harakat('content')}; // "الجَمَاعَةُ مَا وَافَقَ الحَقّ ، أَنْتَ الجَمَاعَةُ وَلَو كُنْتَ وَحْدَكْ ."
  echo $note->{ar_searchable('content')}; // "الجماعة ما وافق الحق انت الجماعة ولو كنت وحدك"

  // When spacing_after_punctuation_only is set to `true` in configuration

  $seriousContentPoliticiansDoNotLike = <<<Arabic
  - قال المُزني: سألتُ الشافعي عن مسألة في "الكلام"، فقال: سَلني عن شيء إذا أخطأتَ فيه قُلتُ "أخطأتَ!"، ولا تسألني عن شيء إذا أخطأتَ فيه قُلتُ "كفرتَ".
  Arabic;

  $note->update(['content' => $seriousContentPoliticiansDoNotLike]);

  echo $note->content;
  // - قال المزني: سألت الشافعي عن مسألة في "الكلام"، فقال: سلني عن شيء إذا أخطأت فيه قلت "أخطأت!"، ولا تسألني عن شيء إذا أخطأت فيه قلت "كفرت".
  echo $note->{ar_with_harakat('content')};
  // - قال المُزني: سألتُ الشافعي عن مسألة في "الكلام"، فقال: سَلني عن شيء إذا أخطأتَ فيه قُلتُ "أخطأتَ!"، ولا تسألني عن شيء إذا أخطأتَ فيه قُلتُ "كفرتَ".
  echo $note->{ar_searchable('content')};
  // قال المزني سالت الشافعي عن مسالة في الكلام فقال سلني عن شيء اذا اخطات فيه قلت اخطات ولا تسالني عن شيء اذا اخطات فيه قلت كفرت
  ```

>[!NOTE]<br>Notice how we can use the global helper functions (`ar_with_harakat`, `ar_searchable`, and `ar_indian`) to get the corresponding property name quickly.

> [!IMPORTANT]<br>A validation method is employed during text processing to ensure that the text is free of punctuation anomalies that could impact spacing adjustments.

### Common Arabic Text

You must ensure that the migration, model, factory, and seeder are all set in place in order for this feature to be utilized.

Among many other filtering methods that [`Arabic`](./src/Arabic.php) facade provides, there is a `removeCommons` one. Use it to filter those out to help you search for more focused context.

You can combine that with whole filtered [`ArabicFilter::forSearch`](./src/ArabicFilter.php#40) searches ahead to ensure that you didn't miss the quote itself first, and so on...


## API

- Here is a table of all the available [custom](src/Concerns/HasArabicableMigrationBlueprintMacros.php) migration blueprint macro columns:

  | Macro Name         | MySQL Type     | Maximum Characters or Size               |
  |--------------------|----------------|---------------------------------------------------|
  | `indianDate`       | `date`, `varchar` | Varchar: 10                        |
  | `arabicString`     | `varchar`      | 255 - 65,535 characters                                              |
  | `arabicTinyText`   | `tinytext`     | ~255 characters (equivalent to VARCHAR(255))     |
  | `arabicText`       | `text`         | ~65,535 characters                               |
  | `arabicMediumText` | `mediumtext`   | ~16,777,215 characters                           |
  | `arabicLongText`   | `longtext`     | ~4,294,967,295 characters                        |

  - And keep in mind the following:

    - Each can be passed an `$isNullable` boolean argument, which affects **all** columns.
    - Each can be passed an `$isUnique` boolean argument, which affects the **original** column.
    - `arabicString` can be passed a `$length` integer argument. 
    - Both `arabicString` and `arabicTinyText` can be passed a `$supportsFullSearch` argument, affecting their **'searchable'** column. 
    - Finally `arabicText`, `arabicMediumText`, and `arabicLongText` all do have full-text search index set on their **'searchable'** column.


- Below are the tables of all the `Arabicable` package helpers:

  | **ArabicFilter Facade Methods**                | Description                                                                                         |
  |-----------------------|-----------------------------------------------------------------------------------------------------|
  | `withHarakat(string $text): string`         | Enhances Arabic text by converting numerals to Indian, normalizing spaces, converting punctuation to Arabic, and refining spaces around punctuation marks. Configurable to add spaces before marks based on application settings. |
  | `withoutHarakat(string $text): string`      | Applies the `withHarakat` enhancements and then removes diacritic marks from the text.               |
  | `forSearch(string $text): string`           | Prepares text for search by removing diacritics, all punctuation, converting numerals to Arabic and Indian sequences, deduplicating these sequences, normalizing letters, and spaces. |

  <br>

  | **Arabic Facade Methods**                                       | Description                                                                                     |
  |----------------------------------------------|-------------------------------------------------------------------------------------------------|
  | `removeHarakat(string $text): string`                             | Removes diacritic marks from Arabic text.                                                       |
  | `normalizeHuroof(string $text): string`                           | Normalizes Arabic letters to a consistent form by standardizing various forms of similar letters.|
  | `getSingulars(string\|array $plurals, bool $uniqueFiltered = true): array`                           | Returns the singular Arabic words corresponding to the singular Arabic plural words passed in. It also **caches** the Arabic plural words during the process.|
  | `getPlurals(string\|array $singulars, bool $uniqueFiltered = true): array`                           | Returns the plural Arabic words corresponding to the singular Arabic singular words passed in. It also **caches** the Arabic plural words during the process.|
  | `removeCommons(string\|array $words, array $excludedTypes = [], bool $asString = false): string\|array`                           | Removes common Arabic phrases and unnecessary single characters. It works with a sentence string and an array of words, and it also **caches** all the common Arabic text during the process.|
  | `clearConceptCache(ArabicLinguisticConcept $concept): void`                           | Clears the linguistic concept's cache so that the records will be re-evaluated during the next concept related calls. This is useful when their seeders get updated with new records.|
  | `convertNumeralsToIndian(string $text): string`                   | Converts Arabic numerals to their Indian numeral equivalents.                                   |
  | `convertNumeralsToArabicAndIndianSequences(string $text): string` | Converts sequences of numerals in text to both Arabic and Indian numerals, presenting both versions side by side. |
  | `deduplicateArabicAndIndianNumeralSequences(string $text): string`| Removes duplicate numeral sequences, keeping unique ones at the end of the text.                |
  | `convertPunctuationMarksToArabic(string $text): string`       | Converts common foreign punctuation marks to their Arabic equivalents.                          |
  | `removeAllPunctuationMarks(string $text): string`             | Removes all and every punctuation mark there is; including enclosings and everything.                                                    |
  | `validateForTextSpacing(string $text): void`             | Prepares the text for proper spacing by ensuring there is no inconsistency when it comes to the couples of enclosing marks and so on...                                                    |
  | `normalizeSpaces(string $text): string`             | Gets read of all the extra spacing (consecutives) in between or around the text.                                                    |
  | `addSpacesBeforePunctuationMarks(string $text, array $inclusions = [], array $exclusions = []): string`       | Adds spaces before punctuation marks unless the mark is preceded by another mark or whitespace. |
  | `addSpacesAfterPunctuationMarks(string $text, array $inclusions = [], array $exclusions = []): string`        | Adds spaces after punctuation marks unless the mark is followed by another mark.                |
  | `removeSpacesAroundPunctuationMarks(string $text, array $inclusions = [], array $exclusions = []): string`    | Removes spaces around punctuation marks.                                                        |
  | `removeSpacesWithinEnclosingMarks(string $text, array $exclusions = []): string`      | Removes spaces immediately inside enclosing marks.                                              |
  | `refineSpacesBetweenPunctuationMarks(string $text): string`   | Refines spacing around punctuation marks based on configurations and special rules.             |

  <br>

  | **Global Functions**                                       | Description                                                                                     |
  |----------------------------------------------|-------------------------------------------------------------------------------------------------|
  | `arabicable_special_characters(array\|ArabicSpecialCharacters $only = [], array\|ArabicSpecialCharacters $except = [], bool $combineInstead = false): array`                             | A quick helper to access the Laravel configuration [setting](./config/arabicable.php#L16) that contains all the special characters that are dealt with everywhere! For more details, you can check out [ArabicSpecialCharacters](./src/Enums/ArabicSpecialCharacters.php) enum that's also being utilized under the hood.                                                       |

  <br>

  | **Laravel Validation Rules**                                       | Description                                                                                     |
  |----------------------------------------------|-------------------------------------------------------------------------------------------------|
  | `Arabic(bool $withHarakat = false, bool $withPunctuation = false`                             | A basic Arabic [custom](https://laravel.com/docs/validation#custom-validation-rules) validation rule.                                                       |
  | `ArabicWithSpecialCharacters(ArabicSpecialCharacters\|array $except = [], ArabicSpecialCharacters\|array $only = [])`                             | A more thoroughly studied rule with the same ArabicSpecialCharacters helper in mind. Defaulting to allowing "all" by default, of course.                                                       |
  | `UncommonArabic(array $excludedTypes = [])`                             | A quick way to validate against common Arabic [types](./src/Enums/CommonArabicTextType.php).                                                       |

  <br>

### Package Development

- Change the `localTimezone` to yours in the [`TestCase`] file.

### Changelogs

You can check out the package's [changelogs](https://app.whatthediff.ai/changelog/github/vpremiss/PACKAGE_LARAVEL_arabicable) online via WTD.

### Progress

You can also checkout the project's [roadmap](https://github.com/orgs/VPremiss/projects/5) among others in the organization's dedicated section for [projects](https://github.com/orgs/VPremiss/projects).


## Support

Support ongoing package maintenance as well as the development of **other projects** through [sponsorship](https://github.com/sponsors/VPremiss) or one-time [donations](https://github.com/sponsors/VPremiss?frequency=one-time&sponsor=VPremiss) if you prefer.

And may Allah accept your strive; aameen.

### License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).

### Credits

- [ChatGPT](https://chat.openai.com)
- [Graphite](https://graphite.dev)
- [Laravel](https://github.com/Laravel)
- [Spatie](https://github.com/spatie)
- [BeyondCode](https://beyondco.de)
- [The Contributors](../../contributors)
- All the [backend packages](./composer.json#19) and services this package relies on...
- And the generous individuals that we've learned from and been supported by throughout our journey...

### Inspiration

- [LinuxScout](https://github.com/linuxscout?tab=repositories)
- `emohamed@umail.iu.edu` for the original plurals list
- [Quest](https://github.com/caneara/quest) package
- [AR-PHP](https://github.com/khaled-alshamaa/ar-php) package
- [AR-PHP-Laravel](https://github.com/still-code/ar-php-laravel) package

</div>

<div align="center">
    <br>والحمد لله رب العالمين
</div>

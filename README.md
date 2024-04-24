<div align="center">
    بسم الله الرحمن الرحيم
</div>

# Arabicable

Several effective strategies for managing Arabic text.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vpremiss/arabicable.svg?style=for-the-badge)](https://packagist.org/packages/vpremiss/arabicable)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/vpremiss/arabicable/run-tests.yml?branch=main&label=tests&style=for-the-badge&color=forestgreen)](https://github.com/vpremiss/arabicable/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/vpremiss/arabicable.svg?style=for-the-badge&color=b07d00)](https://packagist.org/packages/vpremiss/arabicable)


## Description

The gist of the matter here is to rely on the database to store variances of each Arabic or Indian numeral field. Having their dedicated fields makes indexing and searching efficient; combined with the right choosing from all available migration types based on character capactiy. And while utilizing Laravel's Eloquent [observers](https://laravel.com/docs/eloquent#observers), we can process only what's necessary again during updates.

So based on the model's property length requirement, we've added migration blueprint [macros](src/Concerns/HasArabicableMigrationBlueprintMacros.php) for Arabic string, tinyText, text, mediumText, and longText; plus a date one for Indian numerals. Using those string or text ones would generate 2 extra columns for each arabic column (a [configurable](config/arabicable.php) **affix**, that is). And the indian date one would generate a `column_indian` to hold that in.

Finally, take a look at the list of offered methods below (the [API](#API) section) to understand what kind of processing we're doing on the text in order to essentially preserve the `column` (without harakat), the `column_with_harakat`, and the `column_searchable` that's well-prepared exactly for that...


## Installation

- Install the package via [composer](https://getcomposer.org):

  ```bash
  composer require vpremiss/arabicable
  ```

>[!NOTE]The config file as well as the migration table will be published automatically.

- Run the package [Artisan](https://laravel.com/docs/artisan) installer using this command:

  ```bash
  php artisan arabicable:install
  ```

### Upgrading (from v1)

- First, make you sure you copy or memorize your current [config/arabicable.php](config/arabicable.php) configuration.

- Then just ensure that the package configuration is re-published using this Artisan command:

  ```bash
  php artisan vendor:publish --tag="arabicable-config" --force
  ```


## Usage

Alright, so let's imagine we have Note(s) and we want to have their content arabicable!

- First create add an arabicable column to its [migration](https://laravel.com/docs/migrations):

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

- Then let's make the model arabicable, activating the observer:

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
  $note = Note::create([
      'content' => '"الجَمَاعَةُ مَا وَافَقَ الحَقّ، أَنْتَ الجَمَاعَةُ وَلَو كُنْتَ وَحْدَكْ."',
  ]);

  // When spacing_after_punctuation_only is set to `false` in configuration (default)

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
  | `withHarakat`         | Enhances Arabic text by converting numerals to Indian, normalizing spaces, converting punctuation to Arabic, and refining spaces around punctuation marks. Configurable to add spaces before marks based on application settings. |
  | `withoutHarakat`      | Applies the `withHarakat` enhancements and then removes diacritic marks from the text.               |
  | `forSearch`           | Prepares text for search by removing diacritics, all punctuation, converting numerals to Arabic and Indian sequences, deduplicating these sequences, normalizing letters, and spaces. |

  <br>

  | **Arabic Facade Methods**                                       | Description                                                                                     |
  |----------------------------------------------|-------------------------------------------------------------------------------------------------|
  | `removeHarakat`                             | Removes diacritic marks from Arabic text.                                                       |
  | `normalizeHuroof`                           | Normalizes Arabic letters to a consistent form by standardizing various forms of similar letters.|
  | `convertNumeralsToIndian`                   | Converts Arabic numerals to their Indian numeral equivalents.                                   |
  | `convertNumeralsToArabicAndIndianSequences` | Converts sequences of numerals in text to both Arabic and Indian numerals, presenting both versions side by side. |
  | `deduplicateArabicAndIndianNumeralSequences`| Removes duplicate numeral sequences, keeping unique ones at the end of the text.                |
  | `convertPunctuationMarksToArabic`       | Converts common foreign punctuation marks to their Arabic equivalents.                          |
  | `validateForTextSpacing`             | Validate whether the text is suitable for spacing.                                                    |
  | `normalizeSpaces`             | Gets read of all the extra spacing (consecutives) in between or around the text.                                                    |
  | `removeAllPunctuationMarks`             | Removes all punctuation marks from the text.                                                    |
  | `addSpacesBeforePunctuationMarks`       | Adds spaces before punctuation marks unless the mark is preceded by another mark or whitespace. |
  | `addSpacesAfterPunctuationMarks`        | Adds spaces after punctuation marks unless the mark is followed by another mark.                |
  | `removeSpacesAroundPunctuationMarks`    | Removes spaces around punctuation marks.                                                        |
  | `removeSpacesWithinEnclosingMarks`      | Removes spaces immediately inside enclosing marks.                                              |
  | `refineSpacesBetweenPunctuationMarks`   | Refines spacing around punctuation marks based on configurations and special rules.             |

  <br>

### Changelogs

You can check out the package's [changelogs](https://app.whatthediff.ai/changelog/github/VPremiss/Arabicable) online via WTD.


## Support

Support ongoing package maintenance as well as the development of **other projects** through [sponsorship](https://github.com/sponsors/VPremiss) or one-time [donations](https://github.com/sponsors/VPremiss?frequency=one-time&sponsor=VPremiss) if you prefer.

And may Allah accept your strive; aameen.

### License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).

### Credits

- [ChatGPT](https://chat.openai.com)
- [Laravel](https://github.com/Laravel)
- [Spatie](https://github.com/spatie)
- [Graphite](https://graphite.dev)
- [WTD](https://whatthediff.ai)
- [All Contributors](../../contributors)
- And the generous individuals that we've learned from and been supported by throughout our journey...

### Inspiration

- [LinuxScout](https://github.com/linuxscout)
- [Quest](https://github.com/caneara/quest) package
- [AR-PHP](https://github.com/khaled-alshamaa/ar-php) package
- [AR-PHP-Laravel](https://github.com/still-code/ar-php-laravel) package


<div align="center">
   <br>والحمد لله رب العالمين
</div>

<div align="center">
    بسم الله الرحمن الرحيم
</div>

# Arabicable

Several effective strategies for managing Arabic text.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vpremiss/arabicable.svg?style=flat-square)](https://packagist.org/packages/vpremiss/arabicable)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/vpremiss/arabicable/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/vpremiss/arabicable/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/vpremiss/arabicable/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/vpremiss/arabicable/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/vpremiss/arabicable.svg?style=flat-square)](https://packagist.org/packages/vpremiss/arabicable)


## Description

The gist of the matter here is to rely on the database to store variances of each Arabic or Indian numeral field. Having their dedicated fields makes indexing and searching efficient; combined with the right choosing from all available migration types based on character capactiy. And while utilizing Laravel's Eloquent [observers](https://laravel.com/docs/eloquent#observers), we can process only what's necessary again during updates.

So based on the model's property length requirement, we've added migration blueprint [macros](src/Concerns/HasArabicBlueprintMacros.php) for Arabic string, tinyText, text, mediumText, and longText; plus a date one for Indian numerals. Using those string or text ones would generate 2 extra columns for each arabic column (a [configurable](config/arabicable.php) **affix**, that is). And the indian date one would generate a `column_indian` to hold that in.

Finally, take a look at the list of offered methods below (the [API](#API) section) to understand what kind of processing we're doing on the text in order to essentially preserve the `column` (without harakat), the `column_with_harakat`, and the `column_searchable` that's well-prepared exactly for that...


## Installation

- Ensure that [Quest](https://github.com/caneara/quest) package is installed; experimenting for the time-being!

- Install the package via [composer](https://getcomposer.org):

  ```bash
  composer require vpremiss/arabicable
  ```

- Publish the [config file](config/arabicable.php) using this [Artisan](https://laravel.com/docs/artisan) command:

  ```bash
  php artisan vendor:publish --tag="arabicable-config"
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
      'content' => '"الجَمَاعَةُ مَا وَافَقَ الحَقّ ، أَنْتَ الجَمَاعَةُ وَلَو كُنْتَ وَحْدَكْ ."',
  ]);

  echo $note->content; // TODO
  echo $note->{ar_with_harakat('content')}; // TODO
  echo $note->{ar_searchable('content')}; // TODO
  ```

  > [!NOTE]<br>Notice how we can use the global helper functions (`ar_with_harakat`, `ar_searchable`, and `ar_indian`) to get the corresponding property name quickly.

## API

- Here is a table of all the available [custom](src/Concerns/HasArabicBlueprintMacros.php) migration blueprint macro columns:

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

  | **ArabicFilter Facade Methods**                           | Description                                                                                     |
  |----------------------------------|-------------------------------------------------------------------------------------------------|
  | `forSearch`               | Prepares text for searching by removing various characters and normalizing numerals and letters. |
  | `withHarakat`             | Prepares text with Harakat, adjusts numeral formats, and formats spacing and punctuation.       |
  | `withoutHarakat`          | Prepares text without Harakat, also adjusting numeral formats and spacing around punctuation.   |

  <br>

  | **Arabic Facade Methods** (extends Text)                            | Description                                                                          |
  |-----------------------------------|--------------------------------------------------------------------------------------|
  | `removeHarakat`                   | Removes diacritic marks from Arabic text.                                            |
  | `normalizeHuroof`                 | Normalizes Arabic letters to a consistent form.                                      |
  | `convertToFasila`                 | Converts commas in text to Arabic commas (،).                                        |

  <br>

  | **Text Facade Methods**                             | Description                                                                          |
  |-----------------------------------------------------|--------------------------------------------------------------------------------------|
  | `removePunctuationMarks`                           | Removes common punctuation marks from the text.                                      |
  | `removeEnclosingMarks`                             | Removes enclosing marks like quotes and brackets from the text.                      |
  | `addSpacesAroundPunctuationMarks`                  | Adds spaces around punctuation marks.                                                |
  | `addSpacesForColonsAfterDoubleQuotes`              | Adds a space after colons that follow double quotes.                                 |
  | `removeEmptySpaces`                                | Removes extra spaces, leaving only single spaces between words.                      |
  | `removeSpacesWithinQuotes`                         | Removes all spaces within quotes, keeping the text tightly together.                  |
  | `convertNumeralsToIndian`                          | Converts Western numerals to their Indian numeral equivalents.                        |
  | `convertNumeralsToArabicAndIndianSequences`        | Translates each sequence of numerals in the text to both Arabic and Indian numerals, appending both versions side by side. |
  | `deduplicateArabicAndIndianNumeralSequences`       | Deduplicates numeral sequences in the text and consolidates them at the end of the text, ensuring only unique numeral sequences are present. |

  <br>

### Changelogs

You can check out the package's [changelogs](https://app.whatthediff.ai/changelog/github/VPremiss/Arabicable) online via WTD.


## Support

Support ongoing package maintenance as well as the development of **other projects** through [sponsorship](https://github.com/sponsors/VPremiss) or one-time [donations](https://github.com/sponsors/VPremiss?frequency=one-time&sponsor=VPremiss) if you prefer.

And may Allah accept your strive; aameen.

### License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).

### Credits

- [Quest](https://github.com/caneara/quest) package
- [ChatGPT](https://chat.openai.com)
- [Laravel](https://github.com/Laravel)
- [Spatie](https://github.com/spatie)
- [WTD](https://whatthediff.ai)
- [All Contributors](../../contributors)
- And the generous individuals that we've learned from and been supported by throughout our journey...


<div align="center">
   <br>والحمد لله رب العالمين
</div>

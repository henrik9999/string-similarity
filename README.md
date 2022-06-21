# string-similarity

Finds degree of similarity between two strings, based on [Dice's Coefficient](http://en.wikipedia.org/wiki/S%C3%B8rensen%E2%80%93Dice_coefficient), which is mostly better than [Levenshtein distance](http://en.wikipedia.org/wiki/Levenshtein_distance).

This implementation actually treats multiple occurrences of a bigram as unique. The correctness of this behavior is most easily seen when getting the similarity between "GG" and "GGGGGGGG", which should obviously not be 1.

This is a PHP implemenation of the Node.js package [string-similarity](https://github.com/aceakash/string-similarity)

## Usage
Install using:

```shell
composer require henrik9999/string-similarity
```

In your code:

```php
$stringSimilarity = new StringSimilarity();

$similarity = $stringSimilarity->compareTwoStrings("healed", "sealed");

$matches = $stringSimilarity->findBestMatch("healed", [
  "edward",
  "sealed",
  "theatre",
]);
```

## API

The package contains two methods:

### compareTwoStrings(string $string1, string $string2, bool $casesensitive)

Returns a fraction between 0 and 1, which indicates the degree of similarity between the two strings. 0 indicates completely different strings, 1 indicates identical strings. The comparison is case-sensitive by default.

##### Arguments

1. string1 (string): The first string
2. string2 (string): The second string
2. casesensitive (bool): If the comparison should be case-sensitive

Order does not make a difference.

##### Returns

(number): A fraction from 0 to 1, both inclusive. Higher number indicates more similarity.

##### Examples

```php
$stringSimilarity->compareTwoStrings("healed", "sealed");
// → 0.8

$stringSimilarity->compareTwoStrings(
  "Olive-green table for sale, in extremely good condition.",
  "For sale: table in very good  condition, olive green in colour."
);
// → 0.6060606060606061

$stringSimilarity->compareTwoStrings(
  "Olive-green table for sale, in extremely good condition.",
  "For sale: green Subaru Impreza, 210,000 miles"
);
// → 0.2558139534883721

$stringSimilarity->compareTwoStrings(
  "Olive-green table for sale, in extremely good condition.",
  "Wanted: mountain bike with at least 21 gears."
);
// → 0.1411764705882353
```

### findBestMatch(string mainString, array targetStrings, bool $casesensitive)

Compares `mainString` against each string in `targetStrings`.

##### Arguments

1. mainString (string): The string to match each target string against.
2. targetStrings (array): Each string in this array will be matched against the main string.
3. casesensitive (bool): If the comparison should be case-sensitive.

##### Returns

(Object): An object with a `ratings` property, which gives a similarity rating for each target string, a `bestMatch` property, which specifies which target string was most similar to the main string, and a `bestMatchIndex` property, which specifies the index of the bestMatch in the targetStrings array.

##### Examples

```php
$stringSimilarity->findBestMatch('Olive-green table for sale, in extremely good condition.', [
  'For sale: green Subaru Impreza, 210,000 miles',
  'For sale: table in very good condition, olive green in colour.',
  'Wanted: mountain bike with at least 21 gears.'
]);
// →
array(3) {
  ["ratings"]=>
  array(3) {
    [0]=>
    array(2) {
      ["target"]=>
      string(45) "For sale: green Subaru Impreza, 210,000 miles"
      ["rating"]=>
      float(0.2558139534883721)
    }
    [1]=>
    array(2) {
      ["target"]=>
      string(62) "For sale: table in very good condition, olive green in colour."
      ["rating"]=>
      float(0.6060606060606061)
    }
    [2]=>
    array(2) {
      ["target"]=>
      string(45) "Wanted: mountain bike with at least 21 gears."
      ["rating"]=>
      float(0.1411764705882353)
    }
  }
  ["bestMatch"]=>
  array(2) {
    ["target"]=>
    string(62) "For sale: table in very good condition, olive green in colour."
    ["rating"]=>
    float(0.6060606060606061)
  }
  ["bestMatchIndex"]=>
  int(1)
}
```

## Release Notes

### 1.0.1
- Made some perfomance improvements

### 1.0.0
- Initial Release
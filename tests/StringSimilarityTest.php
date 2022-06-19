<?php

namespace Henrik9999\StringSimilarity\Test;

use Henrik9999\StringSimilarity\StringSimilarity;
use PHPUnit\Framework\TestCase;

class StringSimilarityTest extends TestCase
{
    /**
     * @dataProvider compareTwoStringsData
     */
    public function testCompareTwoStrings(string $first, string $second, $expected, $casesensitive = true): void
    {
        $stringSimilarity = new StringSimilarity();

        $this->assertEquals($expected, $stringSimilarity->compareTwoStrings($first, $second, $casesensitive));
    }

    public function compareTwoStringsData(): array
    {
        return [
            ['AA', 'AAAA', 0.5],
            ['french', 'quebec', 0],
            ['france', 'france', 1],
            ['fRaNce', 'france', 0.2],
            ['healed', 'sealed', 0.8],
            ['web applications', 'applications of the web', 0.7878787878787878],
            ['Olive-green table for sale, in extremely good condition.', 'For sale: table in very good  condition, olive green in colour.', 0.6060606060606061],
            ['Olive-green table for sale, in extremely good condition.', 'For sale: green Subaru Impreza, 210,000 miles', 0.2558139534883721],
            ['Olive-green table for sale, in extremely good condition.', 'Wanted: mountain bike with at least 21 gears.', 0.1411764705882353],
            ['this has one extra word', 'this has one word', 0.7741935483870968],
            ['a', 'a', 1],
            ['a', 'b', 0],
            ['', '', 1],
            ['a', '', 0],
            ['', 'a', 0],
            ['', 'a', 0],
            ['apple event', 'apple    event', 1],
            ['iphone', 'iphone x', 0.9090909090909091],
            ['Hello', 'hello', 0.75],
            ['Hello', 'hello', 1, false],
        ];
    }

    public function testFindBestMatch()
    {
        $stringSimilarity = new StringSimilarity();

        $matches = $stringSimilarity->findBestMatch('healed', ['mailed', 'edward', 'sealed', 'theatre']);

        $this->assertEquals(
            [
                ['target' => 'mailed', 'rating' => 0.4],
                ['target' => 'edward', 'rating' => 0.2],
                ['target' => 'sealed', 'rating' => 0.8],
                ['target' => 'theatre', 'rating' => 0.36363636363636365],
            ], $matches['ratings']
        );

        $this->assertEquals(
            ['target' => 'sealed', 'rating' => 0.8], $matches['bestMatch']
        );

        $this->assertEquals(2, $matches['bestMatchIndex']);
    }
}
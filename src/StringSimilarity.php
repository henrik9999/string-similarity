<?php

namespace Henrik9999\StringSimilarity;

class StringSimilarity
{
    public function compareTwoStrings(string $first, string $second, bool $casesensitive = true)
    {

        $first = preg_replace('/\s+/', '', $first);
        $second = preg_replace('/\s+/', '', $second);

        if (!$casesensitive) {
            $first = mb_strtolower($first);
            $second = mb_strtolower($second);
        }

        if ($first === $second) return 1;

        $firstLength = mb_strlen($first);
        $secondLength =  mb_strlen($second);
        if ($firstLength < 2 || $secondLength < 2) return 0;


        $firstBigrams = [];
        for ($i = 0; $i < $firstLength - 1; $i++) {
            $bigram = mb_substr($first, $i, 2);
            $count = isset($firstBigrams[$bigram]) ? ++$firstBigrams[$bigram] : 1;

            $firstBigrams[$bigram] = $count;
        }

        $intersectionSize = 0;
        for ($i = 0; $i < $secondLength - 1; $i++) {
            $bigram = mb_substr($second, $i, 2);
            $count = $firstBigrams[$bigram] ?? 0;

            if ($count > 0) {
                $firstBigrams[$bigram] = $count - 1;
                $intersectionSize++;
            }
        }

        return (2.0 * $intersectionSize) / ($firstLength + $secondLength - 2);
    }

    public function findBestMatch(string $mainString, array $targetStrings, bool $casesensitive = true): array
    {
        if (count($targetStrings) < 1) throw new \Error('Bad arguments: First argument should be a string, second should be an array of strings');

        $ratings = [];
        $bestMatchIndex = 0;
        foreach ($targetStrings as $i => $currentTargetString) {
            $currentRating = $this->compareTwoStrings($mainString, $currentTargetString, $casesensitive);
            $ratings[] = ['target' => $currentTargetString, 'rating' => $currentRating];
            if ($currentRating > $ratings[$bestMatchIndex]['rating']) {
                $bestMatchIndex = $i;
            }
        }

        $bestMatch = $ratings[$bestMatchIndex];

        return ['ratings' => $ratings, 'bestMatch' => $bestMatch, 'bestMatchIndex' => $bestMatchIndex];
    }
}
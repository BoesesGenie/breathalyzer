<?php
$time = microtime(true);

const DEBUG_MODE = false;

if (!isset($argv[1])) {
    echo "Input file required.\n";
    exit;
}

if (!file_exists($argv[1])) {
    echo "Wrong file name.\n";
    exit;
}

$input = explode(' ', trim(preg_replace('/\s+/', ' ', file_get_contents($argv[1]))));
$rawVocabulary = explode("\n", strtolower(trim(file_get_contents('vocabulary.txt'))));
$vocabulary = [];
foreach ($rawVocabulary as $dWord) { // Index by string length
    $vocabulary[strlen($dWord)][] = $dWord;
}

$treeKeys = array_keys($vocabulary);
sort($treeKeys);
$treeKeysCnt = count($treeKeys);
$rawVocabulary = array_flip($rawVocabulary);
$dist = 0;
foreach ($input as $uWord) {
    if (isset($rawVocabulary[$uWord])) { // Full match
        continue;
    }

    $min = -1;
    $uLen = strlen($uWord);
    $vocKey = 0;
    $treeKey = 0;
    foreach ($treeKeys as $treeKey => $length) { // Find tree root
        if ($length >= $uLen) {
            break;
        }
    }

    $vocKey = $length;
    $keyDiff = 0;
    $isMaxKey = true;
    do {
        if (isset($vocabulary[$vocKey])) {
            foreach ($vocabulary[$vocKey] as $dWord) {
                if ($min < 0) {
                    $min = levenshtein($uWord, $dWord);
                    continue;
                }

                $newDist = levenshtein($uWord, $dWord);
                if ($newDist < $min) {
                    $min = $newDist;
                    if ($min == 1) { // Shortest distance found
                        break 2;
                    }
                }
            }
        }

        $check = false;
        if ($isMaxKey) {
            ++$keyDiff;
            $treeKey += $keyDiff;
            if (isset($treeKeys[$treeKey])) {
                $vocKey = $treeKeys[$treeKey];
                $check = true;
            } else {
                $vocKey = $treeKeysCnt;
            }
        }

        if (!$isMaxKey) {
            ++$keyDiff;
            $treeKey -= $keyDiff;
            if (isset($treeKeys[$treeKey])) {
                $vocKey = $treeKeys[$treeKey];
                $check = true;
            } else {
                $vocKey = -1;
            }
        }

        if (!$check) {
            break;
        }

        $isMaxKey = !$isMaxKey;
    } while ($min > abs($uLen - $vocKey));

    $dist += $min;
}

echo $dist . "\n";

if (DEBUG_MODE) {
    $time = microtime(true) - $time;
    echo $time . "\n";
}
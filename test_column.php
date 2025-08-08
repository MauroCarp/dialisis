<?php

function getColumnLetter($columnNumber) {
    $columnLetter = '';
    while ($columnNumber > 0) {
        $columnNumber--;
        $columnLetter = chr(65 + ($columnNumber % 26)) . $columnLetter;
        $columnNumber = intval($columnNumber / 26);
    }
    return $columnLetter;
}

echo 'For 58 patients + 1 column (A): ' . getColumnLetter(59) . PHP_EOL;
echo 'Old method would have been: ' . chr(65 + 58) . ' (invalid)' . PHP_EOL;
echo 'Character 123 is: ' . chr(123) . PHP_EOL;

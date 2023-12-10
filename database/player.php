<?php

$positions = ["PG", "SG", "SF", "PF", "C"];

for ($k = 1; $k <= 30; $k++) {
    $numbers = [];
    for ($i = 0; $i < 10; $i++) {
        $number = rand(1, 99);
        while (in_array($number, $numbers)) {
            $number = rand(1, 99);
        }
        //$p = $positions[$i % 5];
        $p = ($i % 5) + 1;
        $sql = "INSERT INTO `players` (`name`, `position`, `jersey_number`, `team_id`) VALUES ('Player $number', '$p', $number, $k);";
        echo $sql . PHP_EOL;
    }
}

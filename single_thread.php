<?php

$totalLoops = 0;

pcntl_async_signals(true);
pcntl_signal(SIGINT, function () use (&$totalLoops) {
    die("PHP " . PHP_VERSION . " looped " . number_format($totalLoops) . " times.\n");
});

while (true) {
    for ($j = 0; $j < 5_000_000; ++$j) {
        // TODO: CPU busy work here
    }
    $totalLoops += 5_000_000;
}


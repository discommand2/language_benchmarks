<?php
$totalLoops = 0;
$shutdownFunction = function () use (&$totalLoops) {
    die("PHP " . PHP_VERSION . " looped " . number_format($totalLoops) . " times.\n");
};
pcntl_async_signals(true);
pcntl_signal(SIGINT, $shutdownFunction);
pcntl_signal(SIGTERM, $shutdownFunction);
for ($totalLoops = 0; true; $totalLoops += 5_000_000) {
    for ($j = 0; $j < 5_000_000; ++$j);
}

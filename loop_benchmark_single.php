<?php
$totalLoops = 0;
$shutdownFunction = function () use (&$totalLoops) {
    die("PHP " . PHP_VERSION . " looped " . number_format($totalLoops) . " times.\n");
};
pcntl_async_signals(true);
pcntl_signal(SIGINT, $shutdownFunction);
pcntl_signal(SIGTERM, $shutdownFunction);
while (true) ++$totalLoops;

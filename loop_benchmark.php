<?php
// called by: 'timeout 10s php loop_benchmark.php'
$count_loops = 0;
pcntl_async_signals(true);
pcntl_signal(SIGTERM, function () use (&$count_loops) {
    die("PHP " . phpversion() . " executed the loop " . number_format($count_loops) . " times in 10 seconds.\n");
});
while (true) ++$count_loops;

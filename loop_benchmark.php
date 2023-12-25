<?php

use parallel\{Runtime, Channel};

$cpuCount = shell_exec("nproc");
$channel = Channel::make('loopCounter', Channel::Infinite);
$totalLoops = 0;
$runtimes = [];
$futures = [];
pcntl_async_signals(true);

$handler = function ($signo) use (&$totalLoops, &$runtimes, &$futures) {
    echo "SIGNAL $signo PHP " . PHP_VERSION . " looped " . number_format($totalLoops) . " times.\n";
    foreach ($futures as $future) $future->kill();
    foreach ($runtimes as $runtime) $runtime->close();
    echo "SIGNAL $signo PHP " . PHP_VERSION . " looped " . number_format($totalLoops) . " times.\n";
    exit(0);
};

pcntl_signal(SIGTERM, $handler);
pcntl_signal(SIGINT, $handler);

for ($i = 0; $i < $cpuCount; $i++) {
    $runtimes[$i] = new Runtime();
    $futures[$i] = $runtimes[$i]->run(function ($channel, $i) {
        while (true) {
            for ($j = 0; $j < 1_000_000; $j++) {
                // This loop will run a million times before moving on
            }
            $channel->send(1_000_000);
        }
    }, [$channel, $i]);
}

while (true) {
    $totalLoops += $channel->recv();
    //echo ("PHP " . PHP_VERSION . " looped " . number_format($totalLoops) . " times.\n");
}

<?php

use parallel\{Runtime, Future, Channel};

$cpuCount = shell_exec("nproc");
$channel = Channel::make('loopCounter', Channel::Infinite);
$totalLoops = 0;
$runtimes = [];
$futures = [];
pcntl_async_signals(true);
pcntl_signal(SIGTERM, function ($signo) use ($totalLoops, $runtimes, $futures) {
    echo ("Got here!");
    foreach ($futures as $future) $future->kill();
    foreach ($runtimes as $runtime) $runtime->close();
    echo "PHP " . PHP_VERSION . " looped " . number_format($totalLoops) . " times.\n";
    exit(0);
});

for ($i = 0; $i < $cpuCount; $i++) {
    $runtimes[$i] = new Runtime();
    $futures[$i] = $runtimes[$i]->run(function ($channel, $i) {
        $countLoops = 0;
        while (true) {
            for ($j = 0; $j < 1_000_000; $j++) {
                // This loop will run a million times before moving on
            }
            $countLoops += 1_000_000;
            $channel->send($countLoops);
            echo ("1,000,000 loops in thread $i\n");
        }
    }, [$channel, $i]);
}

while (true) {
    $totalLoops += $channel->recv();
}

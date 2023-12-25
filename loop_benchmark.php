<?php

use parallel\{Runtime, Future, Channel};

$cpuCount = shell_exec("nproc");
$channel = Channel::make('loopCounter', Channel::Infinite);
$shouldStop = Channel::make('shouldStop');

pcntl_async_signals(true);
$ctrlcHandler = function ($signo) use ($channel, $shouldStop) {
    $totalLoops = 0;
    while ($channel->recv($totalLoops, false)) {
        $totalLoops += $totalLoops;
    }
    echo "PHP " . PHP_VERSION . " looped " . number_format($totalLoops) . " times.\n";
    $shouldStop->send(true); // Signal threads to stop
    exit(0);
};
pcntl_signal(SIGTERM, $ctrlcHandler);

$runtimes = [];
for ($i = 0; $i < $cpuCount; $i++) {
    $runtimes[$i] = new Runtime();
    $runtimes[$i]->run(function ($channel, $i, $shouldStop) {
        $countLoops = 0;
        while (true) {
            for ($j = 0; $j < 1_000_000; $j++) {
                // This loop will run a million times before moving on
            }
            $countLoops += 1_000_000;
            $channel->send($countLoops);
            echo ("1,000,000 loops in thread $i\n");
            if ($shouldStop->poll()) { // Check if we should stop
                break;
            }
        }
    }, [$channel, $i, $shouldStop]);
}

foreach ($runtimes as $runtime) {
    $runtime->close();
}

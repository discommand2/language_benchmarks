<?php

use parallel\{Runtime, Future, Channel};

$cpuCount = shell_exec("nproc");
$channel = Channel::make('loopCounter', Channel::Infinite);

pcntl_async_signals(true);
$ctrlcHandler = function ($signo) use ($channel) {
    $totalLoops = 0;
    while ($channel->recv($totalLoops, false)) {
        $totalLoops += $totalLoops;
    }
    echo "PHP " . PHP_VERSION . " looped " . number_format($totalLoops) . " times.\n";
    exit(0);
};
pcntl_signal(SIGTERM, $ctrlcHandler);

$runtimes = [];
for ($i = 0; $i < $cpuCount; $i++) {
    $runtimes[$i] = new Runtime();
    $runtimes[$i]->run(function ($channel, $i) {
        $countLoops = 0;
        while (true) {
            echo ($i);
            for ($j = 0; $j < 1000000; $j++) {
                // This loop will run a million times before moving on
            }
            $countLoops += 1000000;
            $channel->send($countLoops);
        }
    }, [$channel, $i]);
}

foreach ($runtimes as $runtime) {
    $runtime->close();
}

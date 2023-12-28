<?php

use parallel\{Runtime, Channel};

$totalLoops = 0;
$runtimes = [];
$futures = [];
$cpuCount = shell_exec("nproc");
$channel = Channel::make('loopCounter', Channel::Infinite);

$sigint = function () use (&$totalLoops, &$runtimes, &$futures, $channel) {
    echo "PHP " . PHP_VERSION . " looped " . number_format($totalLoops) . " times.\n";
    foreach ($futures as $i => $future) {
        $future->cancel();
        $runtimes[$i]->close();
    }
    $channel->close();
    exit(0);
};

pcntl_async_signals(true);
pcntl_signal(SIGINT, $sigint);

$run = function ($channel) {
    pcntl_async_signals(true);
    pcntl_signal(SIGINT, function () {
    });

    $sendRuntime = new Runtime();
    $send = function ($value) use ($channel) {
        $channel->send($value);
    };

    while (true) {
        for ($j = 0; $j < 5_000_000; ++$j) {
            // TODO: CPU busy work here
        }
        $sendRuntime->run($send, [$j]);
    }
};

for ($i = 0; $i < $cpuCount / 2; ++$i) {
    $runtimes[$i] = new Runtime();
    $futures[$i] = $runtimes[$i]->run($run, [$channel]);
}

while ($totalLoops += $channel->recv());

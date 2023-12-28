<?php

use parallel\{Runtime, Channel};

$totalLoops = 0;
$runtimes = [];
$futures = [];
$cpuCount = shell_exec("nproc");
$channel = Channel::make('loopCounter', Channel::Infinite);

pcntl_async_signals(true);
pcntl_signal(SIGINT, function () use (&$totalLoops, &$runtimes, &$futures, $channel) {
    echo "PHP " . PHP_VERSION . " looped " . number_format($totalLoops) . " times.\n";
    foreach ($futures as $i => $future) {
        $future->cancel();
        $runtimes[$i]->close();
    }
    $channel->close();
    exit(0);
});

for ($i = 0; $i < $cpuCount / 2; ++$i) {
    $runtimes[$i] = new Runtime();
    $futures[$i] = $runtimes[$i]->run(function ($channel, $i) {
        pcntl_async_signals(true);
        pcntl_signal(SIGINT, function () {
        });
        $two = 2;
        while (true) {
            for ($j = 0; $j < 5_000_000; ++$j) {
                $two += 0;
            }
            $channel->send(5_000_000);
        }
    }, [$channel, $i]);
}

while ($totalLoops += $channel->recv());

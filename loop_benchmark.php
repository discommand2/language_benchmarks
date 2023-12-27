<?php

use parallel\{Runtime, Channel};

$cpuCount = shell_exec("nproc") / 2;
$channel = Channel::make('loopCounter', Channel::Infinite);
$totalLoops = 0;
$runtimes = [];
$futures = [];
pcntl_async_signals(true);

$shutdownFunction = function ($signo) use (&$totalLoops, &$runtimes, &$futures, $channel) {
    echo "PHP " . PHP_VERSION . " looped " . number_format($totalLoops) . " times.\n";
    foreach ($futures as $i => $future) {
        $future->cancel();
        $runtimes[$i]->close();
    }
    $channel->close();
    exit(0);
};

pcntl_signal(SIGINT, $shutdownFunction);
pcntl_signal(SIGTERM, $shutdownFunction);

for ($i = 0; $i < $cpuCount / 2; ++$i) {
    $runtimes[$i] = new Runtime();
    $futures[$i] = $runtimes[$i]->run(function ($channel, $i) {
        pcntl_async_signals(true);
        $handler = function ($signo) {
            // Do Nothing
        };
        pcntl_signal(SIGINT, $handler);
        pcntl_signal(SIGTERM, $handler);
        while (true) {
            for ($j = 0; $j < 5_000_000; ++$j) {
                // TODO: CPU busy work here
            }
            $channel->send(5_000_000);
        }
    }, [$channel, $i]);
}

while ($totalLoops += $channel->recv()) {
    // Do Nothing 
}

<?php

use parallel\{Runtime, Channel};

$cpuCount = shell_exec("nproc");
$channel = Channel::make('loopCounter', Channel::Infinite);
$totalLoops = 0;
$runtimes = [];
$futures = [];
pcntl_async_signals(true);

$shutdownFunction = function () use (&$totalLoops, &$runtimes, &$futures, $channel) {
    echo "PHP " . PHP_VERSION . " looped " . number_format($totalLoops) . " times.\n";
    foreach ($futures as $i => $future) {
        $future->cancel();
        $runtimes[$i]->close();
    }
    $channel->close();
    exit(0);
};

register_shutdown_function($shutdownFunction);

$handler = function ($signo) {
    exit(0);
};

pcntl_signal(SIGINT, $handler);
pcntl_signal(SIGTERM, $handler);

for ($i = 0; $i < $cpuCount; $i++) {
    $runtimes[$i] = new Runtime();
    $futures[$i] = $runtimes[$i]->run(function ($channel, $i) {
        $running = true;
        register_shutdown_function(function () use (&$running, $i) {
            echo ("Shutting down thread $i\n");
            $running = false;
        });
        while ($running) {
            for ($j = 0; $j < 1_000_000; $j++) {
                // This loop will run a million times before moving on
            }
            $channel->send(1_000_000);
        }
    }, [$channel, $i]);
}

while ($totalLoops += $channel->recv()) {
    //echo ("PHP " . PHP_VERSION . " looped " . number_format($totalLoops) . " times.\n");
}

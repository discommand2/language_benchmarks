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
        $matrix1 = [[1, 2], [3, 4]];
        $matrix2 = [[5, 6], [7, 8]];
        $multiply = function ($matrix1, $matrix2) {
            $result = [];
            for ($i = 0; $i < count($matrix1); $i++) {
                for ($j = 0; $j < count($matrix2[0]); $j++) {
                    $result[$i][$j] = 0;
                    for ($k = 0; $k < count($matrix1[0]); $k++) {
                        $result[$i][$j] += $matrix1[$i][$k] * $matrix2[$k][$j];
                    }
                }
            }
            return $result;
        };
        pcntl_async_signals(true);
        pcntl_signal(SIGINT, function () {
        });
        while (true) {
            for ($j = 0; $j < 100_000; ++$j) {
                $multiply($matrix1, $matrix2);
            }
            $channel->send(100_000);
        }
    }, [$channel, $i]);
}

while ($totalLoops += $channel->recv());

<?php

use parallel\{Runtime, Future, Channel};

$numCpus = shell_exec("nproc");
$channel = Channel::make('loops');

$ctrlcHandler = function ($signo) use ($channel) {
    $totalLoops = 0;
    while ($channel->recv()) {
        $totalLoops += 1;
    }
    echo "PHP looped $totalLoops times.\n";
    exit(0);
};

pcntl_signal(SIGTERM, $ctrlcHandler);

$runtimes = [];
for ($i = 0; $i < $numCpus; $i++) {
    $runtimes[] = new Runtime();

    $runtimes[$i]->run(function ($channel) {
        while (true) {
            for ($j = 0; $j < 1000000; $j++) {
                // This loop will run a million times before moving on
            }
            $channel->send(1000000);
        }
    }, [$channel]);
}

foreach ($runtimes as $runtime) {
    $runtime->close();
}

pcntl_signal_dispatch();
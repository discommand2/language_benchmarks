<?php

use parallel\{Runtime, Future, Channel};

$numCpus = shell_exec("nproc");
$channel = Channel::make('loops');
$stopChannel = Channel::make('stop');

$ctrlcHandler = function ($signo) use ($channel, $stopChannel, &$runtimes) {
    $totalLoops = 0;
    while (($loops = $channel->recv()) !== null) {
        $totalLoops += $loops;
    }
    echo "PHP " . phpversion() . " looped $totalLoops times.\n";
    $stopChannel->send(true);
    foreach ($runtimes as $runtime) {
        $runtime->kill();
    }
    exit(0);
};

pcntl_signal(SIGTERM, $ctrlcHandler);

$runtimes = [];
for ($i = 0; $i < $numCpus; $i++) {
    $runtimes[] = new Runtime();

    $runtimes[$i]->run(function ($channel, $stopChannel) {
        while (true) {
            for ($j = 0; $j < 1000000; $j++) {
                // This loop will run a million times before moving on
            }
            try {
                if ($stopChannel->recv(0.001)) {
                    break;
                }
            } catch (\parallel\Channel\Error\Closed $e) {
                // Channel was closed, break the loop
                break;
            }
            $channel->send(1000000);
        }
    }, [$channel, $stopChannel]);
}

while (true) {
    sleep(1);
}
<?php

use parallel\{Runtime, Future, Channel};

$numCpus = shell_exec("nproc");
$channel = Channel::make('loops');

$ctrlcHandler = function ($signo) use ($channel, &$runtimes) {
    $totalLoops = 0;
    while (($loops = $channel->recv()) !== null) {
        if ($loops === 'stop') {
            break;
        }
        $totalLoops += $loops;
    }
    echo "PHP " . phpversion() . " looped $totalLoops times.\n";
    foreach ($runtimes as $runtime) {
        $runtime->kill();
    }
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
            if ($channel->poll()) {
                $message = $channel->recv();
                if ($message === 'stop') {
                    return;
                }
            }
            $channel->send(1000000);
        }
    }, [$channel]);
}

while (true) {
    sleep(1);
}

foreach ($runtimes as $runtime) {
    $runtime->close();
}

pcntl_signal_dispatch();
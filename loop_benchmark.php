<?php

use parallel\{Runtime, Future, Channel, Events};

$numCpus = shell_exec("nproc");
$channel = Channel::make('loops');
$stop = false;

$ctrlcHandler = function ($signo) use ($channel, &$stop, &$runtimes) {
    global $stop;
    $stop = true;
    $totalLoops = 0;
    while (($loops = $channel->recv()) !== null) {
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
        global $stop;
        while (true) {
            for ($j = 0; $j < 1000000; $j++) {
                // This loop will run a million times before moving on
            }
            if ($stop) {
                break;
            }
            $channel->send(1000000);
        }
    }, [$channel]);
}

while (true) {
    sleep(1);
}

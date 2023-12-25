<?php

use parallel\{Channel, Runtime, Events};

// Function that each thread will execute
$threadFunction = function (Channel $channel) {
    $countLoops = 0;
    while (true) {
        for ($i = 0; $i < 1_000_000; $i++) {
            // This loop will run a million times before moving on
        }
        $countLoops += 1_000_000;
        $channel->send($countLoops);
    }
};

// Create an event loop to listen for messages from threads
$events = new Events();

// Create a channel for communication
$channel = Channel::make('countLoops', Channel::Infinite);

// Create an array to store the runtime objects
$runtimes = [];

// Create threads equal to the number of CPUs
$cpuCount = shell_exec('nproc') ?: 1;
for ($i = 0; $i < $cpuCount; $i++) {
    $runtime = new Runtime();
    $runtimes[] = $runtime;
    $runtime->run($threadFunction, [$channel]);
    $events->addChannel($channel);
}

// Register a signal handler for SIGINT (Ctrl+C)
pcntl_async_signals(true);
pcntl_signal(SIGTERM, function () use ($channel) {
    $totalLoops = 0;
    while ($channel->count()) {
        $totalLoops += $channel->recv();
    }
    echo "PHP looped " . number_format($totalLoops) . " times.\n";
    exit(0);
});

// Event loop to accumulate the count of loops from all threads
$totalLoops = 0;
while (true) {
    $event = $events->poll();

    if ($event->type === Events\Event\Type::Read) {
        $totalLoops += $event->value;
        $events->addChannel($channel);
    }

    // You can add some logic here to break the loop if needed
}

// Cleanup
foreach ($runtimes as $runtime) {
    $runtime->close();
}
Channel::close($channel);

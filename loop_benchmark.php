<?php
// called by: 'timeout 10s php loop_benchmark.php'
$cpuCores = shell_exec("nproc"); // Get the number of CPU cores
$runtime = new \parallel\Runtime();

$futures = [];
for ($i = 0; $i < $cpuCores; $i++) {
    $futures[] = $runtime->run(function () {
        $count_loops = 0;
        pcntl_async_signals(true);
        pcntl_signal(SIGTERM, function () use (&$count_loops) {
            die("PHP " . phpversion() . " looped " . number_format($count_loops) . " times.\n");
        });
        while (true) ++$count_loops;
    });
}

// Retrieve the results when ready
foreach ($futures as $future) {
    $result = $future->value();
}

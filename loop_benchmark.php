<?php
// called by: 'timeout 10s php loop_benchmark.php'
$cpuCores = shell_exec("nproc"); // Get the number of CPU cores
$runtime = new \parallel\Runtime();
$futures = [];

for ($i = 0; $i < $cpuCores; $i++) {
    $futures[] = $runtime->run(function () {
        $count_loops = 0;
        while (true) ++$count_loops;
        return $count_loops;
    });
}

pcntl_async_signals(true);
pcntl_signal(SIGTERM, function () use (&$futures) {
    echo ("Got here\n");
    $total_count = 0;
    foreach ($futures as $future) {
        $future->cancel();
        $total_count += $future->value();
    }
    echo "PHP " . phpversion() . " looped $total_count times.\n";
});

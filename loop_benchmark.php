<?php
// called by: 'timeout 10s php loop_benchmark.php'
$cpuCores = shell_exec("nproc"); // Get the number of CPU cores
$runtime = new \parallel\Runtime();
$futures = [];
pcntl_async_signals(true);
pcntl_signal(SIGTERM, function () use (&$futures) {
    $total_count = 0;
    foreach ($futures as $future) $total_count += $future->value();
    echo "PHP " . phpversion() . " looped $total_count times.\n";
});

for ($i = 0; $i < $cpuCores; $i++) {
    $futures[] = $runtime->run(function () {
        $running = true;
        $count_loops = 0;
        pcntl_async_signals(true);
        pcntl_signal(SIGTERM, function () use (&$running) {
            $running = false;
        });
        while ($running) ++$count_loops;
        return $count_loops;
    });
}

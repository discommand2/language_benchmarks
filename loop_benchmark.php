<?php
// called by: 'timeout 10s php loop_benchmark.php'
$cpuCores = shell_exec("nproc"); // Get the number of CPU cores
$runtime = new \parallel\Runtime();
$futures = [];

for ($i = 0; $i < 8; $i++) {
    $futures[] = $runtime->run(function ($i) {
        while (true) echo ($i);
        return 1;
    }, [$i]);
}
$total_count = 0;
foreach ($futures as $future) $total_count += $future->value();
echo ("PHP " . phpversion() . " looped " . number_format($total_count) . " times.\n");

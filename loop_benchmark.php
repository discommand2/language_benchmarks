<?php
// called by: 'timeout 10s php loop_benchmark.php'
$cpuCores = shell_exec("nproc"); // Get the number of CPU cores
$runtime = new \parallel\Runtime();
$futures = [];

for ($i = 0; $i < $cpuCores; $i++) {
    $futures[] = $runtime->run(function () {
        $count_loops = 0;
        while ($count_loops < 1000000000) ++$count_loops;
        return $count_loops;
    });
}
$total_count = 0;
foreach ($futures as $future) $total_count += $future->value();
echo ("PHP " . phpversion() . " looped " . number_format($total_count) . "times\n");

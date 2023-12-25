<?php
// called by: 'timeout 10s php loop_benchmark.php'
$cpuCores = shell_exec("nproc"); //
$runtimes = [];
$futures = [];
for ($i = 0; $i < 8; $i++) {
    $runtimes[$i] = new \parallel\Runtime();
    $futures[$i] = $runtimes[$i]->run(function ($i) {
        for ($j = 0; $j < 5000; $j++) echo ($i);
        return 1;
    }, [$i]);
}
for ($j = 0; $j < 500; $j++) echo ($i);
$total_count = 0;
foreach ($futures as $future) $total_count += $future->value();
echo ("PHP " . phpversion() . " looped " . number_format($total_count) . " times.\n");

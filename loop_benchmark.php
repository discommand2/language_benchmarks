<?php

// Start the timer
$start_time = microtime(true);
$end_time = $start_time + 10;  // 10 second later

// Counter for the number of loop iterations
$count_loops = 0;

// Run the while loop as many times as possible within 1 second
while (microtime(true) < $end_time) {
    $count_loops++;
}

// Output the number of loop iterations performed
$per_second = $count_loops / 10;
$count_loops = number_format($count_loops,0,'.',",");
$per_second = number_format($per_second,1,'.',",");
echo "PHP " . PHP_VERSION . " executed the loop $count_loops times in 10 seconds. ($per_second/sec)\n";

?>

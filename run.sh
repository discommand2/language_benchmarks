timeout -s SIGINT 10s python3.9 loop_benchmark.py
timeout -s SIGINT 10s python3.11 loop_benchmark.py
timeout -s SIGINT 10s python3.12 loop_benchmark.py
timeout -s SIGINT 10s node loop_benchmark.js
timeout -s SIGINT 10s ./loop_benchmark_c++
timeout -s SIGINT 10s java LoopBenchmark
timeout -s SIGINT 10s ./loop_benchmark_go
timeout -s SIGINT 10s target/release/loop_benchmark_rust
timeout -s SIGINT 10s php loop_benchmark.php


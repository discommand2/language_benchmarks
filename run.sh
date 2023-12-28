timeout -s SIGINT -k 2s 10s  pypy3.10 loop_benchmark.py
timeout -s SIGINT -k 2s 10s php loop_benchmark.php
timeout -s SIGINT -k 2s 10s ./loop_benchmark_go
timeout -s SIGINT -k 2s 10s node loop_benchmark.js
timeout -s SIGINT -k 2s 10s LoopBenchmark/bin/Release/net6.0/linux-x64/publish/LoopBenchmark
timeout -s SIGINT -k 2s 10s java LoopBenchmark
timeout -s SIGINT -k 2s 10s target/release/loop_benchmark_rust
timeout -s SIGINT -k 2s 10s ./loop_benchmark_c++


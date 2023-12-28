echo "Testing Python 3.8..."
timeout -s SIGINT -k 2s 10s  python3.8 loop_benchmark.py

echo "Testing Python 3.9..."
timeout -s SIGINT -k 2s 10s  python3.9 loop_benchmark.py

echo "Testing Python 3.10..."
timeout -s SIGINT -k 2s 10s  python3.10 loop_benchmark.py

echo "Testing Python 3.11..."
timeout -s SIGINT -k 2s 10s  python3.11 loop_benchmark.py

echo "Testing Python 3.12..."
timeout -s SIGINT -k 2s 10s  python3.12 loop_benchmark.py

echo "Testing PHP..."
timeout -s SIGINT -k 2s 10s php loop_benchmark.php

echo "Testing Go..."
timeout -s SIGINT -k 2s 10s ./loop_benchmark_go

echo "Testing Node.js..."
timeout -s SIGINT -k 2s 10s node loop_benchmark.js

echo "Testing C#.NET 6.0 ..."
timeout -s SIGINT -k 2s 10s LoopBenchmark/bin/Release/net6.0/linux-x64/publish/LoopBenchmark

echo "Testing C#.NET 7.0 ..."
timeout -s SIGINT -k 2s 10s LoopBenchmark/bin/Release/net7.0/linux-x64/publish/LoopBenchmark

echo "Testing Java..."
timeout -s SIGINT -k 2s 10s java LoopBenchmark

echo "Testing Rust..."
timeout -s SIGINT -k 2s 10s target/release/loop_benchmark_rust

echo "Testing C++..."
timeout -s SIGINT -k 2s 10s ./loop_benchmark_c++


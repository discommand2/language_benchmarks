echo "Compiling C++..."
g++ -std=c++11 -o loop_benchmark_c++ loop_benchmark.cpp

echo "Compiling Go..."
go build -ldflags "-X 'main.goVersion=$(go version | cut -d " " -f 3)'" -o loop_benchmark_go loop_benchmark.go

echo "Compiling Java..."
javac LoopBenchmark.java

echo "Compiling Rust..."
cargo build --release
#!/bin/bash

echo "Compiling C#..."
mkdir -p LoopBenchmark
cd LoopBenchmark
dotnet new console --force
cp ../loop_benchmark.cs Program.cs
dotnet publish -c Release -r linux-x64 --self-contained true

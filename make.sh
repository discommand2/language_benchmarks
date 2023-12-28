echo "Compiling C++..."
g++ -std=c++11 -O3 -march=native -flto -o loop_benchmark_c++ loop_benchmark.cpp -pthread

echo "Compiling Go..."
go build -ldflags "-s -w -X 'main.goVersion=$(go version | cut -d " " -f 3)'" -o loop_benchmark_go loop_benchmark.go

echo "Compiling Java..."
javac LoopBenchmark.java

echo "Compiling Rust..."
cargo rustc --release -- -C debuginfo=0 -C target-cpu=native

echo "Compiling C#.NET..."
mkdir -p LoopBenchmark
cd LoopBenchmark
dotnet new console --force
cp ../loop_benchmark.cs Program.cs
dotnet publish -c Release -r linux-x64 --self-contained true -p:DebugType=None -p:DebugSymbols=false

echo ''
echo "Benchmark Compiling done!"
echo ''


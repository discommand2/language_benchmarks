# Language Benchmarks

Welcome to the Language Benchmarks repository, a comprehensive benchmarking suite designed to measure and compare the performance of various programming languages in executing CPU-intensive tasks.

## Overview

This repository hosts a set of multi-threaded benchmark programs that execute a busy loop, leveraging the full CPU power for a fixed duration of 10 seconds. The benchmarks are written in several popular programming languages, including C++, Go, Java, JavaScript (Node.js), PHP, Python, and Rust.

The benchmarking approach used here can be extended to a wide range of tasks such as sorting arrays, mathematical computations, string manipulations, file system operations, HTTP requests, and JSON encoding/decoding. This project serves as a template for developers to create their own benchmarks and contribute to a growing library of performance tests.

## Getting Started

Before diving into the benchmarks, please consider starring and forking the repository on GitHub to make your own modifications:

https://github.com/discommand2/language_benchmarks

After forking, clone your forked repository to your local machine:

```
git clone https://github.com/YOUR_USERNAME/language_benchmarks.git
cd language_benchmarks
```

Run the `make.sh` script to compile all benchmark programs:

```
./make.sh
```

Execute the `run.sh` script to run all benchmarks:

```
./run.sh
```

Each program will utilize all available CPU cores to execute as many loops as possible within the 10-second window.

## Extending the Benchmarks

The current benchmarks focus on busy loops, but the possibilities for extension are vast. Here are some ideas:

- **Sorting Algorithms**: Compare the performance of different sorting algorithms across languages.
- **Mathematical Computations**: Benchmark operations like prime number generation or matrix multiplication.
- **String Manipulations**: Test operations such as concatenation, searching, and regex.
- **File System Operations**: Measure the speed of file I/O operations.
- **HTTP Requests**: Evaluate the performance of web API interactions.
- **JSON Encoding/Decoding**: Assess the speed of data serialization and deserialization.

## Contributing

Contributions are welcome! If you have a benchmark to add, an improvement to suggest, or a bug to report, please submit a pull request or open an issue on the GitHub repository.

## Support and Community

Join the vibrant community of developers on the discommand2 Discord server for support, discussions, and collaboration:

[https://discord.gg/yMs5vgvyV4](https://discord.gg/yMs5vgvyV4)

## License

This project is licensed under the MIT License. For more details, see the [LICENSE](LICENSE) file in the repository.

---

Dive into the world of performance benchmarking and unleash the full potential of your applications with Language Benchmarks!
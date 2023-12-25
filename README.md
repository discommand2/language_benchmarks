# language_benchmarks

Just wanted to compare some languages on basic low level loops.

Feel free to submit PRs that improve the performance of the benchmarks.

The only requirement is that the script can count the number of times it increments an integer by 1.

## My Results

- PHP 8.3.0 incremented 2,017,331,232 times.
- Python 3.9 incremented 491,194,859 times.
- Python 3.11 incremented 615,760,202 times.
- Node.js v18.14.2 incremented 6,282,006,282 times.
- C++ 11.4.1 incremented 6,250,747,687 times.
- Java 11.0.18 incremented 2,263,794,602 times.
- Rust 1.73.0 incremented 1,497,341,449 times.
- Go go1.21.3 incremented 2,264,357,056 times.

### My Server Specs

- Model: Intel Xeon-E 2386G
- Speed: 3.5 GHz (base) / 4.7 GHz (turbo)
- Cores: 6 Threads: 12
- RAM 3200Mhz

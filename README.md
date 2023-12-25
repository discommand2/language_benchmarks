# language_benchmarks

Just wanted to compare some languages on basic low level loops.

With each iteration the following things occur:

- Get the current time
- Compare the current time with the end time
- Increment in integer counter

## My Results

- PHP 8.3.0 executed the loop 492,407,243 times in 10 seconds. (49,240,724.3/sec)
- Python 3.9.18 executed the loop 120,175,523 times in 10 seconds. (12,017,552.3/sec)
- Python 3.11.5 executed the loop 103,142,174 times in 10 seconds. (10,314,217.4/sec)
- Node.js v18.14.2 executed the loop 386,607,337 times in 10 seconds. (38,660,733.7/sec)
- C++ (version 11.4.1 20230605 (Red Hat 11.4.1-2)) executed the loop 635,155,247 times in 10 seconds. (63,515,524.7/sec)
- Java 11.0.18 executed the loop 858,076,454 times in 10 seconds. (85,807,645.4/sec)
- Rust executed the loop 497,528,089 times in 10 seconds. (49,752,808/sec)
- Go go1.21.3 executed the loop 410,261,149 times in 10 seconds. (41,026,114/sec)

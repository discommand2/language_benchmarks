#include <iostream>
#include <chrono>
#include <iomanip>
#include <locale>

int main() {
    auto start_time = std::chrono::high_resolution_clock::now();
    auto end_time = start_time + std::chrono::seconds(10);

    long long count_loops = 0;

    // Run the while loop as many times as possible within 10 seconds
    while (std::chrono::high_resolution_clock::now() < end_time) {
        count_loops++;
    }

    // Calculate iterations per second
    double per_second = static_cast<double>(count_loops) / 10.0;

    // Output the results with locale formatting for thousands separator
    std::cout.imbue(std::locale(""));
    std::cout << "C++ (version " << __VERSION__ << ") executed the loop " << count_loops 
              << " times in 10 seconds. (" << std::fixed 
              << std::setprecision(1) << per_second << "/sec)" << std::endl;

    return 0;
}

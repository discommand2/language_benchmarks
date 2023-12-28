#include <iostream>
#include <vector>
#include <thread>
#include <atomic>
#include <csignal>
#include <unistd.h>
#include <iomanip>
#include <sstream>

std::atomic<bool> running(true);
std::atomic<unsigned long long> totalLoops(0);

void signalHandler(int signum)
{
    running = false;
}

void loopFunction()
{
    int two = 2;
    while (running)
    {
        for (int j = 0; j < 5000000; ++j)
        {
            two += 0;
        }
        totalLoops += 5000000;
    }
}

std::string formatNumber(unsigned long long number)
{
    std::stringstream ss;
    ss.imbue(std::locale("en_US.UTF-8"));
    ss << std::fixed << number;
    return ss.str();
}

int main()
{
    // Register signal handler for SIGINT
    std::signal(SIGINT, signalHandler);

    // Get the number of CPU cores
    unsigned int cpuCount = std::thread::hardware_concurrency();

    // Create a vector to hold the threads
    std::vector<std::thread> threads;

    // Launch a thread for each CPU core
    for (unsigned int i = 0; i < cpuCount / 2; ++i)
    {
        threads.emplace_back(loopFunction);
    }

    // Wait for all threads to finish
    for (auto &thread : threads)
    {
        thread.join();
    }

    // Output the result
    std::cout.imbue(std::locale("en_US.UTF-8"));
    std::cout << "C++ " << __GNUC__ << "." << __GNUC_MINOR__ << "." << __GNUC_PATCHLEVEL__
              << " looped " << formatNumber(totalLoops) << " times." << std::endl;

    return 0;
}
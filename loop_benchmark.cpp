#include <csignal>
#include <iostream>
#include <atomic>

std::atomic<unsigned long long> count_loops(0);

void signalHandler(int signum) {
    std::cout << "C++ executed the loop " << count_loops << " times before termination." << std::endl;
    exit(signum);
}

int main() {
    signal(SIGTERM, signalHandler);
    while (true) {
        ++count_loops;
    }
    return 0;
}
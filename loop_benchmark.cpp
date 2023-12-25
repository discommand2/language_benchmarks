#include <csignal>
#include <iostream>
#include <locale>

volatile unsigned long long count_loops = 0;

void signalHandler(int signum) {
    std::cout.imbue(std::locale("en_US.UTF-8"));
    std::cout << "C++ " << __GNUC__ << "." << __GNUC_MINOR__ << "." << __GNUC_PATCHLEVEL__ << " incremented " << count_loops << " times." << std::endl;
    exit(signum);
}

int main() {
    signal(SIGTERM, signalHandler);
    while (true) {
        ++count_loops;
    }
    return 0;
}
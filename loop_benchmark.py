import os
import signal
from multiprocessing import Process, Value, cpu_count, current_process
from ctypes import c_uint
from platform import python_version

# Atomic counter that can be safely incremented from multiple processes
class LoopBenchmark:
    def __init__(self):
        self.value = Value(c_uint, 0)  # Shared among processes

    def increment(self, amount):
        with self.value.get_lock():
            self.value.value += amount

    def get(self):
        with self.value.get_lock():
            return self.value.value

# Function to be run by each process
def loop_function(counter):
    while True:
        for _ in range(5_000_000):
            # TODO: CPU busy work here
            pass
        counter.increment(5_000_000)

# Signal handler for graceful shutdown
def shutdown_handler(signum, frame, counter, processes):
    print(f"Python {python_version()} looped {counter.get():,} times.")
    for process in processes:
        os.kill(process.pid, signal.SIGINT)  # Send SIGINT to child process
    os._exit(0)
    
# Main function
def main():
    counter = LoopBenchmark()
    cpu_count_value = cpu_count()

    # Register signal handlers
    original_sigint_handler = signal.signal(signal.SIGINT, signal.SIG_IGN)
    original_sigterm_handler = signal.signal(signal.SIGTERM, signal.SIG_IGN)

    # Create a process pool with one process per CPU core
    processes = []
    for _ in range(cpu_count_value):
        process = Process(target=loop_function, args=(counter,))
        process.start()
        processes.append(process)

    # Restore the original signal handlers
    signal.signal(signal.SIGINT, original_sigint_handler)
    signal.signal(signal.SIGTERM, original_sigterm_handler)

    # Register signal handlers for the main process
    signal.signal(signal.SIGINT, lambda signum, frame: shutdown_handler(signum, frame, counter, processes))
    signal.signal(signal.SIGTERM, lambda signum, frame: shutdown_handler(signum, frame, counter, processes))

    # Wait for all processes to complete
    try:
        for process in processes:
            process.join()
    except KeyboardInterrupt:
        shutdown_handler(signal.SIGINT, None, counter, processes)

if __name__ == "__main__":
    main()

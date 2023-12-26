import os
import signal
import threading
from concurrent.futures import ThreadPoolExecutor
from multiprocessing import cpu_count
from platform import python_version
from ctypes import c_uint

# Atomic counter that can be safely incremented from multiple threads
class LoopBenchmark:
    def __init__(self):
        self.value = c_uint(0)
        self._lock = threading.Lock()

    def increment(self, amount):
        with self._lock:
            self.value.value += amount
            return self.value.value

    def get(self):
        with self._lock:
            return self.value.value

# Function to be run by each thread
def loop_function(counter):
    local_counter = 0
    while True:
        # Simulate some work
        for _ in range(5_000_000):
            pass
        local_counter += 5_000_000
        counter.increment(5_000_000)

# Signal handler for graceful shutdown
def shutdown_handler(signum, frame, counter):
    print(f"Python {python_version()} looped {counter.get():,} times.")
    os._exit(0)

# Main function
def main():
    counter = LoopBenchmark()
    cpu_count_value = cpu_count()

    # Register signal handlers
    signal.signal(signal.SIGINT, lambda signum, frame: shutdown_handler(signum, frame, counter))
    signal.signal(signal.SIGTERM, lambda signum, frame: shutdown_handler(signum, frame, counter))

    # Create a thread pool with one thread per CPU core
    with ThreadPoolExecutor(max_workers=cpu_count_value) as executor:
        for _ in range(cpu_count_value):
            executor.submit(loop_function, counter)

if __name__ == "__main__":
    main()

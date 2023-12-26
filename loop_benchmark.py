import os
import signal
from multiprocessing import cpu_count, Process, Value
from platform import python_version

# Function to be run by each process
def loop_function(counter):
    local_counter = 0
    while True:
        # Simulate some work
        for _ in range(5_000_000):
            pass
        local_counter += 5_000_000
        with counter.get_lock():
            counter.value += 5_000_000

# Signal handler for graceful shutdown
def shutdown_handler(signum, frame, counter):
    print(f"Python {python_version()} looped {counter.value:,} times.")
    os._exit(0)

# Main function
def main():
    counter = Value('i', 0)
    cpu_count_value = cpu_count()

    # Register signal handlers
    signal.signal(signal.SIGINT, lambda signum, frame: shutdown_handler(signum, frame, counter))
    signal.signal(signal.SIGTERM, lambda signum, frame: shutdown_handler(signum, frame, counter))

    # Create a process pool with one process per CPU core
    processes = []
    for _ in range(cpu_count_value):
        p = Process(target=loop_function, args=(counter,))
        p.start()
        processes.append(p)

    for p in processes:
        p.join()

if __name__ == "__main__":
    main()
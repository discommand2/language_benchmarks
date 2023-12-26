from multiprocessing import Process, cpu_count, RawValue
from ctypes import c_uint

class LoopBenchmark:
    def __init__(self):
        self.value = RawValue(c_uint, 0)  # Shared among processes

    def increment(self, amount):
        self.value.value += amount

def loop_function(counter):
    local_counter = 0
    while True:
        for _ in range(5_000_000):
            # TODO: CPU busy work here
            pass
        local_counter += 5_000_000
        if local_counter >= 100_000_000:  # Update shared counter every 100M iterations
            counter.increment(local_counter)
            local_counter = 0

def main():
    counter = LoopBenchmark()
    processes = [Process(target=loop_function, args=(counter,)) for _ in range(cpu_count())]

    try:
        for p in processes:
            p.start()

        for p in processes:
            p.join()
    except KeyboardInterrupt:
        print("Interrupted by user, terminating processes...")
        for p in processes:
            p.terminate()
        for p in processes:
            p.join()

if __name__ == "__main__":
    main()
from multiprocessing import Pool, cpu_count, RawValue
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

    with Pool(cpu_count()) as pool:
        pool.map(loop_function, [counter] * cpu_count())

if __name__ == "__main__":
    main()
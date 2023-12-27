import signal
from multiprocessing import Process, cpu_count, Value
from ctypes import c_uint
import platform
import locale


class LoopBenchmark:
    def __init__(self):
        self.value = Value(c_uint, 0)  # Shared among processes

    def increment(self, amount):
        with self.value.get_lock():  # ensure atomic operation
            self.value.value += amount


def loop_function(counter):
    # Reset signal handlers
    signal.signal(signal.SIGINT, signal.SIG_DFL)
    signal.signal(signal.SIGTERM, signal.SIG_DFL)

    while True:
        for _ in range(5_000_000):
            # TODO: CPU busy work here
            pass
        counter.increment(5_000_000)


def main():
    counter = LoopBenchmark()
    processes = [
        Process(target=loop_function, args=(counter,)) for _ in range(cpu_count() / 2)
    ]

    def stop_processes(signal, frame):
        for p in processes:
            if p.is_alive():  # Only terminate processes that have been started
                p.terminate()
        for p in processes:
            if p.is_alive():  # Only join processes that have been started
                p.join()
        locale.setlocale(locale.LC_ALL, "en_US.utf8")
        formatted_number = locale.format_string(
            "%d", counter.value.value, grouping=True
        )
        print(f"Python {platform.python_version()} looped {formatted_number} times.")
        exit(0)

    signal.signal(signal.SIGINT, stop_processes)
    signal.signal(signal.SIGTERM, stop_processes)

    for p in processes:
        p.start()

    for p in processes:
        p.join()


if __name__ == "__main__":
    main()

import multiprocessing
import os
import signal
import sys


def worker_main(worker_id, total_loops_pipe):
    try:
        two = 2
        while True:
            # TODO: CPU busy work here
            for _ in range(5_000_000):
                two += 0
                pass
            total_loops_pipe.send(5_000_000)
    except KeyboardInterrupt:
        # Handle the interrupt gracefully if needed
        pass


def shutdown(signum, frame, workers, total_loops_pipe):
    if total_loops > 0:
        print(
            f"Python {sys.version.split()[0]} looped {format(total_loops, ',')} times."
        )
    for worker in workers:
        if os.getpid() == worker._parent_pid:  # Check if current process is parent
            if worker.is_alive():
                if worker._popen is not None:
                    worker.terminate()
    total_loops_pipe.close()
    sys.exit(0)


if __name__ == "__main__":
    cpu_count = os.cpu_count()
    total_loops = 0
    workers = []
    parent_conn, child_conn = multiprocessing.Pipe()

    # Set up signal handling for graceful shutdown
    signal.signal(
        signal.SIGINT,
        lambda signum, frame: shutdown(signum, frame, workers, parent_conn),
    )

    # Start worker processes
    for i in range(cpu_count // 2):
        worker = multiprocessing.Process(target=worker_main, args=(i, child_conn))
        workers.append(worker)
        worker.start()

    # Collect results from workers
    try:
        while True:
            total_loops += parent_conn.recv()
    except EOFError:
        # Pipe closed, exit the loop
        pass

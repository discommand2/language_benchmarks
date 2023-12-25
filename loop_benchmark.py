import signal
import sys

def main():
    count_loops = 0

    def handler(signum, frame):
        print(f"Python {sys.version_info.major}.{sys.version_info.minor} executed the loop {count_loops:,} times before termination.")
        sys.exit(0)

    signal.signal(signal.SIGTERM, handler)

    try:
        while True:
            count_loops += 1
    finally:
        print(f"Python {sys.version_info.major}.{sys.version_info.minor} executed the loop {count_loops:,} times before termination.")

if __name__ == "__main__":
    main()
import signal
import sys

def main():
    count_loops = 0

    def handler(signum, frame):
        print(f"Python {sys.version_info.major}.{sys.version_info.minor} incremented {count_loops:,} times.")
        sys.exit(0)

    signal.signal(signal.SIGTERM, handler)

    while True:
        count_loops += 1

main()
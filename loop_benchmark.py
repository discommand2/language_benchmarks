import signal
import sys

count_loops = 0

def handler(signum, frame):
    print(f"Python {sys.version_info.major}.{sys.version_info.minor} executed the loop {count_loops:,} times before termination.")
    sys.exit(0)

signal.signal(signal.SIGTERM, handler)

while True:
    count_loops += 1
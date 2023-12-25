import time
import platform

# Start the timer
start_time = time.time()
end_time = start_time + 10  # 10 seconds later

# Counter for the number of loop iterations
count_loops = 0

# Run the while loop as many times as possible within 10 seconds
while time.time() < end_time:
    count_loops += 1

# Output the number of loop iterations performed
per_second = count_loops / 10
count_loops_formatted = "{:,}".format(count_loops)
per_second_formatted = "{:,.1f}".format(per_second)
print(f"Python {platform.python_version()} executed the loop {count_loops_formatted} times in 10 seconds. ({per_second_formatted}/sec)")

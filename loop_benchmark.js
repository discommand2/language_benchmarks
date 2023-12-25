let count_loops = 0;
process.on('SIGTERM', () => {
    let count_loops_formatted = count_loops.toLocaleString();
    console.log(`Node.js ${process.version} executed the loop ${count_loops_formatted} times.`);
    process.exit(0);
});

function increment() {
    while (count_loops % 1e6 > 0) {
        ++count_loops;
    }
    ++count_loops;
    setImmediate(increment);
}

increment();
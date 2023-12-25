let i = 0;
let count_loops = 0;
process.on('SIGTERM', () => {
    count_loops = (count_loops * 1e6) + count_loops;
    let count_loops_formatted = count_loops.toLocaleString();
    console.log(`Node.js ${process.version} incremented ${count_loops_formatted} times.`);
    process.exit(0);
});

function increment() {
    while (i < 1e6) {
        ++i;
    }
    i = 0;
    ++count_loops;
    setImmediate(increment);
}

increment();
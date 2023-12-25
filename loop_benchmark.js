let count_loops = 0;
let running = true;

process.on('SIGTERM', () => {
    let count_loops_formatted = count_loops.toLocaleString();
    console.log(`Node.js ${process.version} executed the loop ${count_loops_formatted} times.`);
    process.exit(0);
});

function increment() {
    let i = 0;
    while (i < 2e6 && running) {
        ++count_loops;
        ++i;
    }
    setImmediate(increment);
}

increment();
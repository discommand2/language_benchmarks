let count_loops = 0;
let running = true;

process.on('SIGTERM', () => {
    running = false;
    console.log(`Node.js ${process.version} executed the loop ${count_loops} times before termination.`);
    process.exit(0);
});

function increment() {
    let i = 0;
    while (i < 1e6 && running) {
        ++count_loops;
        ++i;
    }
    if (running) setImmediate(increment);
}

increment();
let count_loops = 0;

process.on('SIGTERM', () => {
    console.log(`Node.js ${process.version} executed the loop ${count_loops} times before termination.`);
    process.exit(0);
});

setInterval(() => {
    ++count_loops;
}, 0);
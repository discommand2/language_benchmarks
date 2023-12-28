const { Worker, isMainThread, parentPort, workerData } = require('worker_threads');
const os = require('os');

if (isMainThread) {
    const cpuCount = os.cpus().length;
    let totalLoops = 0;
    const workers = [];

    process.on('SIGINT', shutdown);

    for (let i = 0; i < cpuCount / 2; i++) {
        const worker = new Worker(__filename);
        workers.push(worker);

        worker.on('message', (loops) => {
            totalLoops += loops;
        });

        worker.on('error', (err) => {
            console.error(err);
            shutdown();
        });

        worker.on('exit', (code) => {
            if (code !== 0) {
                console.error(`Worker stopped with exit code ${code}`);
                shutdown();
            }
        });
    }

    function shutdown() {
        console.log(`Node.js ${process.version} looped ${totalLoops.toLocaleString()} times.`);
        workers.forEach(worker => worker.terminate());
        process.exit(0);
    }
} else {
    let two = 2;
    while (true) {
        for (let i = 0; i < 5000000; i++) {
            two += 0;
        }
        parentPort.postMessage(5000000);
    }
}

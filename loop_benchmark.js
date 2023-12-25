const startTime = Date.now();
const endTime = startTime + 10000; // 10 seconds later

let countLoops = 0;

// Run the while loop as many times as possible within 10 seconds
while (Date.now() < endTime) {
    countLoops++;
}

// Calculate the number of loop iterations performed per second
const perSecond = countLoops / 10;
const countLoopsFormatted = countLoops.toLocaleString('en-US');
const perSecondFormatted = perSecond.toLocaleString('en-US', { minimumFractionDigits: 1, maximumFractionDigits: 1 });

console.log(`Node.js ${process.version} executed the loop ${countLoopsFormatted} times in 10 seconds. (${perSecondFormatted}/sec)`);

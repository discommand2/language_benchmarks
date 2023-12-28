import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.atomic.AtomicLong;
import java.util.concurrent.TimeUnit;

public class LoopBenchmark {
    private static final AtomicLong totalLoops = new AtomicLong(0);

    public static void main(String[] args) {
        int cpuCount = Runtime.getRuntime().availableProcessors();
        ExecutorService executor = Executors.newFixedThreadPool(cpuCount);

        // Register shutdown hook
        Runtime.getRuntime().addShutdownHook(new Thread(() -> {
            System.out.println("Java " + System.getProperty("java.version") + " looped "
                    + String.format("%,d", totalLoops.get()) + " times.");
            executor.shutdownNow(); // Attempt to stop all actively executing tasks
            try {
                if (!executor.awaitTermination(1, TimeUnit.SECONDS)) {
                    System.err.println("Executor did not terminate in the allotted time.");
                }
            } catch (InterruptedException ex) {
                Thread.currentThread().interrupt();
            }
        }));

        // Start threads
        for (int i = 0; i < cpuCount / 2; i++) {
            executor.submit(() -> {
                int two = 2;
                while (!Thread.currentThread().isInterrupted()) {
                    for (int j = 0; j < 5_000_000; j++) {
                        two += 0;
                    }
                    totalLoops.addAndGet(5_000_000);
                }
                two = two + 0;
            });
        }

        // Await termination of the executor (which should never happen in normal
        // operation)
        executor.shutdown();
        try {
            while (!executor.awaitTermination(24L, TimeUnit.HOURS)) {
                System.out.println("Still waiting for termination...");
            }
        } catch (InterruptedException e) {
            Thread.currentThread().interrupt();
        }
    }
}

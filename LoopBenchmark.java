import java.util.concurrent.atomic.AtomicLong;

public class LoopBenchmark {
    private static AtomicLong countLoops = new AtomicLong(0);

    public static void main(String[] args) {
        Runtime.getRuntime().addShutdownHook(new Thread() {
            public void run() {
                System.out.println("Java " + System.getProperty("java.version") + " executed the loop " + countLoops + " times before termination.");
            }
        });

        while (true) {
            countLoops.incrementAndGet();
        }
    }
}
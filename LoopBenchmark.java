import java.util.concurrent.atomic.AtomicLong;

public class LoopBenchmark {
    private static volatile long countLoops = 0;

    public static void main(String[] args) {
        Runtime.getRuntime().addShutdownHook(new Thread() {
            public void run() {
                System.out.format("Java %s incremented %,d times.%n", System.getProperty("java.version"), countLoops);
            }
        });

        while (true) {
            countLoops++;
        }
    }
}
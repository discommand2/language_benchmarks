import java.util.concurrent.atomic.LongAdder;

public class LoopBenchmark {
    private static final LongAdder countLoops = new LongAdder();

    public static void main(String[] args) {
        Runtime.getRuntime().addShutdownHook(new Thread() {
            public void run() {
                System.out.format("Java %s executed the loop %,d times before termination.%n", System.getProperty("java.version"), countLoops.sum());
            }
        });

        while (true) {
            countLoops.increment();
        }
    }
}
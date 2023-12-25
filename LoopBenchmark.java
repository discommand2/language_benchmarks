public class LoopBenchmark {

    public static void main(String[] args) {
        long startTime = System.nanoTime();
        long endTime = startTime + 10_000_000_000L; // 10 seconds in nanoseconds

        long countLoops = 0;

        while (System.nanoTime() < endTime) {
            countLoops++;
        }

        double perSecond = countLoops / 10.0;
        String javaVersion = System.getProperty("java.version");

        System.out.format("Java %s executed the loop %,d times in 10 seconds. (%,.1f/sec)%n", 
                          javaVersion, countLoops, perSecond);
    }
}

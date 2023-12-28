using System;
using System.Threading;
using System.Threading.Tasks;
using System.Diagnostics;
using System.Globalization;
using System.Runtime.InteropServices;

class Program
{
    private static long totalLoops = 0;
    private static CancellationTokenSource cts = new CancellationTokenSource();
    private static bool messagePrinted = false;

    static async Task Main(string[] args)
    {
        int cpuCount = Environment.ProcessorCount / 2;

        Console.CancelKeyPress += (sender, eventArgs) =>
        {
            if (messagePrinted) return;

            string netVersion = RuntimeInformation.FrameworkDescription;
            Console.WriteLine($"C#{netVersion} looped {totalLoops.ToString("N0", CultureInfo.InvariantCulture)} times.");
            messagePrinted = true;
            cts.Cancel();
            eventArgs.Cancel = true;
        };

        Task[] tasks = new Task[cpuCount];

        for (int i = 0; i < cpuCount; i++)
        {
            tasks[i] = Task.Run(() => DoWork(cts.Token));
        }

        try
        {
            await Task.WhenAll(tasks);
        }
        catch (OperationCanceledException)
        {
        }
    }

    static void DoWork(CancellationToken token)
    {
        int two = 2;
        while (!token.IsCancellationRequested)
        {
            // Simulate CPU work
            for (int j = 0; j < 5_000_000; j++)
            {
                two += 0;
            }
            Interlocked.Add(ref totalLoops, 5_000_000);
        }
    }
}
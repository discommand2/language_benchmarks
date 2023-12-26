using System;
using System.Threading;
using System.Threading.Tasks;
using System.Diagnostics;
using System.Globalization;

class Program
{
    private static long totalLoops = 0;
    private static CancellationTokenSource cts = new CancellationTokenSource();

    static async Task Main(string[] args)
    {
        int cpuCount = Environment.ProcessorCount;

        Console.CancelKeyPress += (sender, eventArgs) =>
        {
            Console.WriteLine($"C# .NET looped {totalLoops.ToString("N0", CultureInfo.InvariantCulture)} times.");
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
        while (!token.IsCancellationRequested)
        {
            // Simulate CPU work
            for (int j = 0; j < 5_000_000; j++)
            {
                // TODO: busy work here
            }
            Interlocked.Add(ref totalLoops, 5_000_000);
        }
    }
}

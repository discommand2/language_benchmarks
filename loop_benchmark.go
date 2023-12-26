package main

import (
    "fmt"
    "os"
    "os/signal"
    "runtime"
    "sync"
    "sync/atomic"
    "syscall"
)

func main() {
    var totalLoops uint64
    cpuCount := runtime.NumCPU()
    var wg sync.WaitGroup
    wg.Add(cpuCount)

    // Setting up channel to listen for interrupt signal
    sigChan := make(chan os.Signal, 1)
    signal.Notify(sigChan, syscall.SIGINT, syscall.SIGTERM)

    // Goroutine to handle the interrupt signal
    go func() {
        <-sigChan
        fmt.Printf("Go looped %d times.\n", atomic.LoadUint64(&totalLoops))
        os.Exit(0)
    }()

    // Creating a goroutine for each CPU core
    for i := 0; i < cpuCount; i++ {
        go func() {
            defer wg.Done()
            var localLoops uint64
            for {
                for j := 0; j < 5000000; j++ {
                    // This loop simulates work
                }
                localLoops += 5000000
                atomic.AddUint64(&totalLoops, 5000000)
            }
        }()
    }

    // Wait for all goroutines to finish (they won't, as they are in an infinite loop)
    wg.Wait()
}

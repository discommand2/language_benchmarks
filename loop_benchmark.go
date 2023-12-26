package main

import (
	"fmt"
	"math/big"
	"os"
	"os/signal"
	"runtime"
	"sync"
	"sync/atomic"
	"syscall"
)

var goVersion string

func main() {
	var totalLoops uint64
	cpuCount := runtime.NumCPU()
	var wg sync.WaitGroup
	wg.Add(cpuCount)

	sigChan := make(chan os.Signal, 1)
	signal.Notify(sigChan, syscall.SIGINT, syscall.SIGTERM)

	go func() {
		<-sigChan
		bigTotalLoops := big.NewInt(0).SetUint64(atomic.LoadUint64(&totalLoops))
		fmt.Printf("%s looped %#v times.\n", goVersion, bigTotalLoops)
		os.Exit(0)
	}()

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
	
	wg.Wait()
}
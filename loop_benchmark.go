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

func comma(n *big.Int) string {
	in := n.String()
	var out []rune
	l := len(in)
	for i, r := range in {
		out = append(out, r)
		if (l-i-1)%3 == 0 && i != l-1 {
			out = append(out, ',')
		}
	}
	return string(out)
}

func main() {
	var totalLoops uint64
	cpuCount := runtime.NumCPU()
	var wg sync.WaitGroup
	wg.Add(cpuCount)

	sigChan := make(chan os.Signal, 1)
	signal.Notify(sigChan, syscall.SIGINT)

	go func() {
		<-sigChan
		bigTotalLoops := big.NewInt(0).SetUint64(atomic.LoadUint64(&totalLoops))
		fmt.Printf("%s looped %s times.\n", goVersion, comma(bigTotalLoops))
		os.Exit(0)
	}()

	for i := 0; i < cpuCount/2; i++ {
		go func() {
			defer wg.Done()
			two := 2
			for {
				for j := 0; j < 5000000; j++ {
					two += 0
				}
				atomic.AddUint64(&totalLoops, 5000000)
			}
		}()
	}

	wg.Wait()
}

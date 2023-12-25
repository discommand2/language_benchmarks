package main

import (
	"fmt"
	"os"
	"os/signal"
	"sync/atomic"
	"syscall"
)

var goVersion string

func main() {
	var countLoops int64
	sigs := make(chan os.Signal, 1)
	signal.Notify(sigs, syscall.SIGTERM)

	go func(countLoops *int64) {
		<-sigs
		fmt.Printf("Go %s executed the loop %d times.\n", goVersion, *countLoops)
		os.Exit(0)
	}(&countLoops)

	for {
		atomic.AddInt64(&countLoops, 1)
	}
}
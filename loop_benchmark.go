package main

import (
	"fmt"
	"os"
	"os/signal"
	"syscall"
	"time"
)

var goVersion string

func main() {
	countLoops := 0
	sigs := make(chan os.Signal, 1)
	signal.Notify(sigs, syscall.SIGTERM)

	go func() {
		<-sigs
		fmt.Printf("Go %s executed the loop %d times.\n", goVersion, countLoops)
		os.Exit(0)
	}()

	for {
		countLoops++
		time.Sleep(time.Millisecond) // This is to prevent the loop from consuming too much CPU
	}
}
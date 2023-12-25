package main

import (
	"fmt"
	"os"
	"os/signal"
	"strconv"
	"strings"
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
		fmt.Printf("Go %s incremented %s times.\n", goVersion, comma(*countLoops))
		os.Exit(0)
	}(&countLoops)

	for {
		atomic.AddInt64(&countLoops, 1)
	}
}

func comma(v int64) string {
	s := strconv.FormatInt(v, 10)
	var parts []string
	for len(s) > 0 {
		length := len(s)
		if length > 3 {
			length = 3
		}
		parts = append([]string{s[len(s)-length:]}, parts...)
		s = s[:len(s)-length]
	}
	return strings.Join(parts, ",")
}
package main

import (
	"fmt"
	"io/ioutil"
	"regexp"
	"strconv"
	"strings"
)

func main() {
	content, _ := ioutil.ReadFile("../input.txt")
	lines := strings.Split(string(content), "\n")
	currentGuard := 0
	guardRE := regexp.MustCompile(`Guard #(\d+)`)
	timeRE := regexp.MustCompile(`^\[\d{4}-(\d+-\d+) (\d+):(\d+)\]`)
	var timeAsleep map[int]int
	timeAsleep = make(map[int]int)
	var sleepLog map[int]map[int]int
	sleepLog = make(map[int]map[int]int)
	sleepStart := 0
	for _, line := range lines {
		timeMatch := timeRE.FindStringSubmatch(line)
		day := timeMatch[1]
		hour := timeMatch[2]
		min := timeMatch[3]
		switch true {
		case strings.Contains(line, "wakes"):
			fmt.Printf("%s %s:%s\n", day, hour, min)
			fmt.Printf("%d wakes\n", currentGuard)
			minStart, _ := strconv.Atoi(min)
			timeSpent := minStart - sleepStart
			timeAsleep[currentGuard] += timeSpent
			fmt.Printf("Slept for %d minutes\n", timeSpent)

			for m := sleepStart; m < sleepStart+timeSpent; m++ {
				sleepLog[currentGuard][m]++
			}

		case strings.Contains(line, "falls"):
			fmt.Printf("%s %s:%s\n", day, hour, min)
			fmt.Printf("%d falls asleep\n", currentGuard)
			sleepStart, _ = strconv.Atoi(min)

		case strings.Contains(line, "Guard"):
			fmt.Printf("\n%s %s:%s\n", day, hour, min)
			matches := guardRE.FindStringSubmatch(line)
			currentGuard, _ = strconv.Atoi(matches[1])
			fmt.Printf("%d started shift\n", currentGuard)
			if _, ok := sleepLog[currentGuard]; !ok {
				sleepLog[currentGuard] = make(map[int]int)
			}
		}
	}

	maxMin := -1
	maxMinAmount := -1
	maxGuard := -1
	for guard := range sleepLog {
		for minute, amount := range sleepLog[guard] {
			if amount > maxMinAmount {
				maxMinAmount = amount
				maxMin = minute
				maxGuard = guard
			}
		}
	}
	fmt.Printf("Guard %d sleeps most frequently of all guards at minute %d\n", maxGuard, maxMin)
}

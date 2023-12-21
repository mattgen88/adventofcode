package main

import (
	"fmt"
	"regexp"
	"strconv"
	"strings"

	utilities "github.com/mattgen88/adventofcode/2023"
)

func main() {
	PartOne()
	PartTwo()
}
func readColumns(s string) (columns []int) {
	r := regexp.MustCompile(`\W+`)
	splits := r.Split(s, -1)[1:]
	for _, split := range splits {
		val, _ := strconv.Atoi(split)
		columns = append(columns, val)
	}
	return
}
func readColumns2(s string) (columns []int) {
	s = strings.Replace(s, " ", "", -1)
	r := regexp.MustCompile(`:`)
	splits := r.Split(s, -1)[1:]
	for _, split := range splits {
		val, _ := strconv.Atoi(split)
		columns = append(columns, val)
	}
	return
}
func PartOne() {
	fmt.Println("Part 1")
	input := utilities.ReadInputSlice("input.txt")
	times := readColumns(input[0])
	distances := readColumns(input[1])
	// generate the possible winning scenarios for each
	// race `times` that will beat the record `distances`
	// for each millisecond button held, boat goes 1 mm/s more

	// iterate over races
	answer := 1
	for i := 0; i < len(times); i++ {
		// iterate over scenarios
		time := times[i]
		record_distances := distances[i]
		possibilities := 0
		for j := 0; j < time; j++ {
			// hold for j
			// travel at j mm/s for distance-j seconds
			distance_traveled := j * (time - j)
			if distance_traveled > record_distances {
				possibilities++
			}
		}
		if possibilities > 0 {
			answer = answer * possibilities
		}
	}
	fmt.Printf("Answer %d\n", answer)

}

func PartTwo() {
	fmt.Println("Part 2")
	input := utilities.ReadInputSlice("input.txt")
	times := readColumns2(input[0])
	distances := readColumns2(input[1])

	// iterate over races... of which there is 1
	answer := 1
	for i := 0; i < len(times); i++ {
		// iterate over scenarios
		time := times[i]
		record_distances := distances[i]
		possibilities := 0
		for j := 0; j < time; j++ {
			// hold for j
			// travel at j mm/s for distance-j seconds
			distance_traveled := j * (time - j)
			if distance_traveled > record_distances {
				possibilities++
			}
		}
		if possibilities > 0 {
			answer = answer * possibilities
		}
	}
	fmt.Printf("Answer %d\n", answer)
}

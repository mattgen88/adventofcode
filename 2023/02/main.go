package main

import (
	"fmt"
	utilities "github.com/mattgen88/adventofcode/2023"
	"math"
	"regexp"
	"strconv"
	"strings"
)

func main() {
	partOne()
	partTwo()
}
func partOne() {
	fmt.Println("Part 1")

	gameRegexp := regexp.MustCompile(`^Game (\d+): (.*)$`)
	cubes := map[string]int{
		"red":   12,
		"green": 13,
		"blue":  14,
	}
	sum := 0
	utilities.ReadInputSliceAndDo("input.txt", func(line string) {
		fmt.Println(line)
		matches := gameRegexp.FindAllStringSubmatch(line, -1)
		possible := true
		for match, scores := range strings.Split(matches[0][2], ";") {
			for draw, score := range strings.Split(scores, ",") {
				s := strings.SplitN(strings.TrimLeft(score, " "), " ", 2)
				count, _ := strconv.Atoi(s[0])
				color := s[1]
				if count > cubes[color] {
					fmt.Println("game ", matches[0][1], "match ", match, "draw", draw, "not possible")
					possible = false
				} else {
					fmt.Println("game ", matches[0][1], "match ", match, "draw", draw, "possible")
				}
			}
		}
		if possible {
			fmt.Println("Game ", matches[0][1], "possible")
			gameNum, _ := strconv.Atoi(matches[0][1])
			sum = sum + gameNum
		} else {
			fmt.Println("Game ", matches[0][1], "not possible")
		}
	})
	fmt.Println(sum)
}
func partTwo() {
	fmt.Println("Part 2")
	gameRegexp := regexp.MustCompile(`^Game (\d+): (.*)$`)
	sum := 0
	utilities.ReadInputSliceAndDo("input.txt", func(line string) {
		cubes := map[string]int{
			"red":   0,
			"green": 0,
			"blue":  0,
		}
		fmt.Println(line)
		matches := gameRegexp.FindAllStringSubmatch(line, -1)
		for _, scores := range strings.Split(matches[0][2], ";") {
			for _, score := range strings.Split(scores, ",") {
				s := strings.SplitN(strings.TrimLeft(score, " "), " ", 2)
				count, _ := strconv.Atoi(s[0])
				color := s[1]
				cubes[color] = int(math.Max(float64(cubes[color]), float64(count)))
			}
		}
		fmt.Println(cubes)
		power := cubes["green"] * cubes["red"] * cubes["blue"]
		sum = sum + power
	})
	fmt.Println(sum)
}

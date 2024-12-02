package main

import (
	"fmt"
	"math"
	"regexp"
	"slices"
	"strconv"

	utilities "github.com/mattgen88/adventofcode/2024"
)

func main() {
	input := utilities.ReadInputSlice("input.txt")
	r := regexp.MustCompile(`^(\d+)\s+(\d+)$`)
	listLeft := []int{}
	listRight := []int{}
	for _, row := range input {
		matches := r.FindStringSubmatch(row)
		leftInt, _ := strconv.ParseInt(matches[1], 10, 0)
		listLeft = append(listLeft, int(leftInt))
		rightint, _ := strconv.ParseInt(matches[2], 10, 0)
		listRight = append(listRight, int(rightint))
	}
	slices.Sort(listLeft)
	slices.Sort(listRight)
	Part1(listLeft, listRight)
	Part2(listLeft, listRight)
}

func Part1(listLeft, listRight []int) {

	sum := 0.0
	for i := 0; i < len(listLeft); i++ {
		sum += math.Abs(float64(listLeft[i] - listRight[i]))
	}
	fmt.Printf("The answer for part 1 is %d\n", int(sum))
}

func Part2(listLeft, listRight []int) {
	valueMap := make(map[int]int)
	for _, i := range listRight {
		if value, ok := valueMap[i]; ok {
			valueMap[i] = value + 1
		} else {
			valueMap[i] = 1
		}
	}
	score := 0
	for _, i := range listLeft {
		score += valueMap[i] * i
	}
	fmt.Printf("The answer for part 2 is %d\n", score)
}

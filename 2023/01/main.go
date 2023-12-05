package main

import (
	"fmt"
	utilities "github.com/mattgen88/adventofcode/2023"
	"regexp"
	"strconv"
)

func main() {
	partOne()
	partTwo()
}

func partOne() {
	fmt.Println("Part one")
	input := utilities.ReadInputSlice("input.txt")
	filter := regexp.MustCompile(`[^0-9]+`)
	sum := 0
	for _, line := range input {
		fmt.Println(line)
		line = filter.ReplaceAllString(line, "")
		fmt.Println(line)
		first := string(line[0])
		last := string(line[len(line)-1])
		calibrationString := fmt.Sprintf("%s%s", first, last)
		fmt.Println(calibrationString)
		calibration, _ := strconv.Atoi(calibrationString)
		sum = sum + calibration
	}
	fmt.Println(sum)

}

func partTwo() {
	fmt.Println("Part two")
	input := utilities.ReadInputSlice("input.txt")

	sum := 0
	for _, line := range input {
		first := findFirstNumber(line)
		last := findLastNumber(line)
		calibrationString := fmt.Sprintf("%s%s", first, last)
		fmt.Println(calibrationString)
		calibration, _ := strconv.Atoi(calibrationString)
		sum = sum + calibration
		fmt.Println()
	}
	fmt.Println(sum)
}

func findFirstNumber(line string) string {
	numMap := map[string]string{
		"one":   "1",
		"two":   "2",
		"three": "3",
		"four":  "4",
		"five":  "5",
		"six":   "6",
		"seven": "7",
		"eight": "8",
		"nine":  "9",
	}
	fmt.Println(line)
	for index := 0; index < len(line); {
		char := line[index]
		if char >= 49 && char <= 57 {
			return string(char)
		}
		for k, v := range numMap {
			if len(k) <= len(line)-index {
				if k == line[index:index+len(k)] {
					index = index + len(k)
					return v
				}
			}
		}
		index++
	}
	panic("this shouldn't happen")
}
func findLastNumber(line string) string {
	line = reverse(line)
	numMap := map[string]string{
		"eno":   "1",
		"owt":   "2",
		"eerht": "3",
		"ruof":  "4",
		"evif":  "5",
		"xis":   "6",
		"neves": "7",
		"thgie": "8",
		"enin":  "9",
	}
	fmt.Println(line)
	for index := 0; index < len(line); {
		char := line[index]
		if char >= 49 && char <= 57 {
			return string(char)
		}
		for k, v := range numMap {
			if len(k) <= len(line)-index {
				if k == line[index:index+len(k)] {
					index = index + len(k)
					return v
				}
			}
		}
		index++
	}
	panic("this shouldn't happen")
}
func reverse(s string) string {
	runes := []rune(s)
	for i, j := 0, len(runes)-1; i < j; i, j = i+1, j-1 {
		runes[i], runes[j] = runes[j], runes[i]
	}
	return string(runes)
}

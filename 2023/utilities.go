package utilities

import (
	"fmt"
	"os"
	"strings"
)

func ReadInputSlice(file string) []string {
	h, err := os.ReadFile(file)
	if err != nil {
		panic(err)
	}
	return strings.Split(string(h), "\n")
}

func ReadInputSliceAndDo(file string, f func(line string)) {
	input := ReadInputSlice(file)
	for _, line := range input {
		f(line)
	}
}

func ReadInputGrid(file string, f func([][]rune)) {
	var grid [][]rune
	row := 0
	ReadInputSliceAndDo(file, func(line string) {
		grid = append(grid, []rune{})
		for _, n := range line {
			grid[row] = append(grid[row], n)
		}
		row++
	})
	f(grid)
}

func PrintGrid(grid [][]rune) {
	for _, row := range grid {
		for _, cell := range row {
			fmt.Printf("%c", cell)
		}
		fmt.Println()
	}
}

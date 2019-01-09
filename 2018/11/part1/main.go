package main

import "fmt"

const serial = 7139
const gridSize = 300

func main() {
	grid := newGrid(gridSize)
	setPower(grid, 8)
	if getFuelCell(grid, 3, 5) != 4 {
		panic("bad integrity check")
	}

	grid = newGrid(gridSize)
	setPower(grid, 57)
	if getFuelCell(grid, 122, 79) != -5 {
		panic("bad integrity check")
	}

	grid = newGrid(gridSize)
	setPower(grid, 39)
	if getFuelCell(grid, 217, 196) != 0 {
		panic("bad integrity check")
	}

	grid = newGrid(gridSize)
	setPower(grid, 71)
	if getFuelCell(grid, 101, 153) != 4 {
		panic("bad integrity check")
	}

	grid = newGrid(gridSize)
	setPower(grid, 18)
	if x, y := bestGroup(grid); x != 33 || y != 45 {
		panic(fmt.Sprintf("bad integrity check: %d,%d", x, y))
	}

	grid = newGrid(gridSize)
	grid = setPower(grid, serial)
	fmt.Println(bestGroup(grid))
}

func bestGroup(grid [][]int) (int, int) {
	maxLevel := -999
	var x, y int
	for row := 0; row < len(grid)-3; row++ {
		for col := 0; col < len(grid[row])-3; col++ {
			level := grid[row][col] + grid[row][col+1] + grid[row][col+2] +
				grid[row+1][col] + grid[row+1][col+1] + grid[row+1][col+2] +
				grid[row+2][col] + grid[row+2][col+1] + grid[row+2][col+2]
			if level > maxLevel {
				maxLevel = level
				x = row + 1
				y = col + 1
			}
		}
	}
	return x, y
}

func getFuelCell(grid [][]int, x, y int) int {
	if x < 1 || y < 1 || x >= 300 || y >= 300 {
		panic("get out of bounds")
	}
	return grid[x-1][y-1]
}

func setFuelCell(grid [][]int, x, y, value int) {
	if x < 1 || y < 1 || x >= 300 || y >= 300 {
		panic("set out of bounds")
	}
	grid[x-1][y-1] = value
}

func newGrid(gridSize int) [][]int {
	var grid [][]int
	grid = make([][]int, gridSize)
	for row := 0; row < gridSize; row++ {
		grid[row] = make([]int, gridSize)
		for col := 0; col < gridSize; col++ {
			grid[row][col] = 0
		}
	}
	return grid
}

func setPower(grid [][]int, serial int) [][]int {
	for row := 0; row < gridSize; row++ {
		for col := 0; col < gridSize; col++ {
			grid[row][col] = power(serial, row+1, col+1)
		}
	}
	return grid
}

func power(serial, x, y int) int {
	id := x + 10
	powerLevel := id * (y)
	powerLevel += serial
	powerLevel *= id
	powerLevel = powerLevel / 100
	powerLevel = powerLevel % 10
	powerLevel -= 5
	return powerLevel
}

func print(g [][]int) {
	for row := 0; row < len(g); row++ {
		for col := 0; col < len(g[row]); col++ {
			fmt.Printf("%3d", g[row][col])
		}
		fmt.Println()
	}
}

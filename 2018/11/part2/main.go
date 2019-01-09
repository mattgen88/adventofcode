package main

import "fmt"

const serial = 7139
const gridSize = 300

func main() {
	fmt.Println("Checking integrity, please wait...")
	grid := newGrid(gridSize)
	setPower(grid, 8)
	if getFuelCell(grid, 3, 5) != 4 {
		panic("bad integrity check")
	}
	fmt.Println("1/7")

	grid = newGrid(gridSize)
	setPower(grid, 57)
	if getFuelCell(grid, 122, 79) != -5 {
		panic("bad integrity check")
	}
	fmt.Println("2/7")

	grid = newGrid(gridSize)
	setPower(grid, 39)
	if getFuelCell(grid, 217, 196) != 0 {
		panic("bad integrity check")
	}
	fmt.Println("3/7")

	grid = newGrid(gridSize)
	setPower(grid, 71)
	if getFuelCell(grid, 101, 153) != 4 {
		panic("bad integrity check")
	}
	fmt.Println("4/7")

	grid = newGrid(gridSize)
	setPower(grid, 18)
	if _, x, y := bestGroup(grid, 3); x != 33 || y != 45 {
		panic(fmt.Sprintf("bad integrity check: %d,%d", x, y))
	}
	fmt.Println("5/7")

	max := -99999
	x := 0
	y := 0
	size := 0

	grid = newGrid(gridSize)
	grid = setPower(grid, 18)

	for i := 1; i < 300; i++ {

		val, newX, newY := bestGroup(grid, i)
		if val > max {
			max = val
			x = newX
			y = newY
			size = i
		}
	}
	if max != 113 || x != 90 || y != 269 || size != 16 {
		panic(fmt.Sprintf("bad integrity check: %d, %d, %d", x, y, size))
	}
	fmt.Println("6/7")

	max = -99999
	x = 0
	y = 0
	size = 0

	grid = newGrid(gridSize)
	grid = setPower(grid, 42)

	for i := 1; i < 300; i++ {

		val, newX, newY := bestGroup(grid, i)
		if val > max {
			max = val
			x = newX
			y = newY
			size = i
		}
	}
	if max != 119 || x != 232 || y != 251 || size != 12 {
		panic(fmt.Sprintf("bad integrity check: %d, %d, %d", x, y, size))
	}
	fmt.Println("7/7")
	fmt.Println("done")

	max = -99999
	x = 0
	y = 0
	size = 0

	grid = newGrid(gridSize)
	grid = setPower(grid, serial)

	for i := 1; i < 300; i++ {

		val, newX, newY := bestGroup(grid, i)
		if val > max {
			max = val
			x = newX
			y = newY
			size = i
		}
	}
	fmt.Printf("%d,%d,%d", x, y, size)
}

func bestGroup(grid [][]int, size int) (int, int, int) {
	maxLevel := -999
	var x, y int
	for row := 0; row < len(grid)-size; row++ {
		for col := 0; col < len(grid[row])-size; col++ {
			level := 0
			for i := 0; i < size; i++ {
				for j := 0; j < size; j++ {
					level += grid[row+i][col+j]
				}
			}
			if level > maxLevel {
				maxLevel = level
				x = row + 1
				y = col + 1
			}
		}
	}
	return maxLevel, x, y
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

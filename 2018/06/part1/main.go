package main

import (
	"fmt"
	"io/ioutil"
	"math"
	"strconv"
	"strings"
)

const pointnames = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"

type point struct {
	X    int
	Y    int
	Name string
}

func (p *point) Parse(s string) {
	parts := strings.Split(s, ", ")
	p.X, _ = strconv.Atoi(parts[0])
	p.Y, _ = strconv.Atoi(parts[1])
}

func (p *point) ToString() string {
	return fmt.Sprintf("%d,%d", p.X, p.Y)
}

func main() {
	content, _ := ioutil.ReadFile("../input.txt")
	//content, _ := ioutil.ReadFile("../demo.txt")
	lines := strings.Split(strings.Trim(string(content), "\n"), "\n")
	var points map[string]*point
	points = make(map[string]*point)
	var infPoints map[string]bool
	infPoints = make(map[string]bool)
	maxX := 0
	maxY := 0
	minX := -1
	minY := -1
	// each line is x, y
	// parse and find max x and y to figure out grid size
	for i, line := range lines {
		p := &point{Name: string(pointnames[i])}
		p.Parse(line)

		// Find min X
		if minX == -1 || p.X < minX {
			minX = p.X
		}
		// Find min Y
		if minY == -1 || p.Y < minY {
			minY = p.Y
		}

		// Find max X
		if p.X > maxX {
			maxX = p.X
		}
		// Find max y
		if p.Y > maxY {
			maxY = p.Y
		}
		points[p.ToString()] = p
	}

	// Make grid of appropriate size
	// Mark each point in grid
	var grid map[int]map[int]string
	grid = make(map[int]map[int]string)
	for col := 0; col <= maxX; col++ {
		grid[col] = make(map[int]string)
		for row := 0; row <= maxY; row++ {
			grid[col][row] = "."
			if p, ok := points[fmt.Sprintf("%d,%d", col, row)]; ok {
				grid[col][row] = p.Name
				if col == minX || col == maxX || row == minY || row == maxY {
					infPoints[p.ToString()] = true
				}
			}
		}
	}

	var closestPoints map[string]int
	closestPoints = make(map[string]int)
	// for each point in our grid
	// if point isn't a "coordinate"
	// find closest "coordinate" and increment closet points for coordinate
	// find coordinate with most points
	for row := 0; row <= maxY; row++ {
		for col := 0; col <= maxX; col++ {
			pp := &point{X: col, Y: row}
			if _, ok := points[pp.ToString()]; ok {
				// coordinate is a point, skip
				continue
			}
			if row < minX || row > maxX || col < minY || col > maxY {
				// point is in infinite plane
				continue
			}
			closestDistance := -1
			var closestPoint *point
			tie := false

			for _, p := range points {
				if pp.ToString() == p.ToString() {
					continue
				}
				distance := manhattanDistance(p, pp)
				if distance == closestDistance {
					tie = true
				}
				if closestDistance == -1 || distance < closestDistance {
					tie = false
					closestDistance = distance
					closestPoint = p
					continue
				}

			}

			// gone through all points
			if !tie {
				if _, ok := infPoints[closestPoint.ToString()]; !ok {
					closestPoints[closestPoint.ToString()]++
					grid[col][row] = strings.ToLower(closestPoint.Name)
				}
			}
		}
	}
	max := 0
	for _, val := range closestPoints {
		if val > max {
			max = val
		}
	}
	printGrid(grid)
	fmt.Println(max + 1)
}

func manhattanDistance(a, b *point) int {
	return int(math.Abs(float64(a.X)-float64(b.X)) + math.Abs(float64(a.Y)-float64(b.Y)))
}

func printGrid(grid map[int]map[int]string) {
	for row := 0; row < len(grid[0]); row++ {
		for col := 0; col < len(grid); col++ {
			fmt.Print(grid[col][row])
		}
		fmt.Println()
	}
}

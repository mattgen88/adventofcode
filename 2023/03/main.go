package main

import (
	"fmt"
	utilities "github.com/mattgen88/adventofcode/2023"
	"strconv"
)

type coordinate struct {
	row int
	col int
}

func (c coordinate) equal(cc coordinate) bool {
	return c.row == cc.row && c.col == cc.col
}

type partMapCell struct {
	partNumber string
	coords     []coordinate
	hasPart    bool
	part       part
}

func (p *partMapCell) setPart(c part) {
	p.hasPart = true
	p.part = c
}
func (p *partMapCell) getPartNumber() int {
	num, _ := strconv.Atoi(p.partNumber)
	return num
}

type part struct {
	coordinate
	partType rune
}

func (p *part) isAdjacentTo(c *partMapCell) bool {
	// for each of the adjacent coordinates in p
	// see if any of c's coordinates overlap
	for _, coord := range c.coords {
		var partCoord coordinate
		partCoord.row = p.row - 1
		partCoord.col = p.col - 1
		if partCoord.equal(coord) {
			c.setPart(*p)
			return true
		}
		partCoord.row = p.row - 1
		partCoord.col = p.col
		if partCoord.equal(coord) {
			c.setPart(*p)
			return true
		}
		partCoord.row = p.row - 1
		partCoord.col = p.col + 1
		if partCoord.equal(coord) {
			c.setPart(*p)
			return true
		}
		partCoord.row = p.row
		partCoord.col = p.col - 1
		if partCoord.equal(coord) {
			c.setPart(*p)
			return true
		}
		partCoord.row = p.row
		partCoord.col = p.col + 1
		if partCoord.equal(coord) {
			c.setPart(*p)
			return true
		}
		partCoord.row = p.row + 1
		partCoord.col = p.col - 1
		if partCoord.equal(coord) {
			c.setPart(*p)
			return true
		}
		partCoord.row = p.row + 1
		partCoord.col = p.col
		if partCoord.equal(coord) {
			c.setPart(*p)
			return true
		}
		partCoord.row = p.row + 1
		partCoord.col = p.col + 1
		if partCoord.equal(coord) {
			c.setPart(*p)
			return true
		}
	}

	return false
}

func main() {
	PartOne()
	PartTwo()
}

func PartOne() {
	utilities.ReadInputGrid("input.txt", func(grid [][]rune) {
		var partData []*partMapCell
		var parts []part
		extractPartNumbers(grid, &partData, &parts, 0, 0, nil)
		sum := 0
		// iterate over each part number
		for _, partDatum := range partData {
			for _, part := range parts {
				// iterate over part
				// check for coordinate match
				if part.isAdjacentTo(partDatum) {
					sum = sum + partDatum.getPartNumber()
				}
			}
		}
		fmt.Println("Sum:", sum)
	})
}

type gearData struct {
	count       int
	partNumbers []int
}

func PartTwo() {
	utilities.ReadInputGrid("input.txt", func(grid [][]rune) {
		var partData []*partMapCell
		var parts []part
		extractPartNumbers(grid, &partData, &parts, 0, 0, nil)
		for _, partDatum := range partData {
			for _, part := range parts {
				// iterate over part
				// check for coordinate match
				if part.isAdjacentTo(partDatum) {
					// do nothing
				}
			}
		}
		sum := 0
		// iterate over each part number
		// see if there are any part numbers with exactly two?
		gears := map[part]*gearData{}
		for _, p := range partData {
			if p.hasPart && p.part.partType == '*' {
				if g, ok := gears[p.part]; ok {
					g.count++
					g.partNumbers = append(g.partNumbers, p.getPartNumber())
				} else {
					gears[p.part] = &gearData{}
					gears[p.part].count++
					gears[p.part].partNumbers = append(gears[p.part].partNumbers, p.getPartNumber())
				}
			}
		}
		for _, g := range gears {
			if g.count == 2 {
				// calc ratio
				sum = sum + (g.partNumbers[0] * g.partNumbers[1])
			}
		}
		fmt.Println("Sum:", sum)
	})
}

func extractPartNumbers(grid [][]rune, partData *[]*partMapCell, parts *[]part, row, col int, cell *partMapCell) {
	char := grid[row][col]
	if char == '.' {
		cell = nil
	} else if char < 48 || char > 57 {
		// part
		cell = nil
		*parts = append(*parts, part{coordinate{row, col}, char})
	} else {
		// part number
		if cell == nil {
			cell = &partMapCell{}
			*partData = append(*partData, cell)
		}
		cell.coords = append(cell.coords, coordinate{row, col})
		cell.partNumber = fmt.Sprintf("%s%s", cell.partNumber, string(char))
	}
	if col+1 >= len(grid[row]) {
		// see if we're at the end of the grid
		if row+1 >= len(grid) {
			// done
			return
		}
		// move to next row
		extractPartNumbers(grid, partData, parts, row+1, 0, cell)
		return
	}
	extractPartNumbers(grid, partData, parts, row, col+1, cell)
}

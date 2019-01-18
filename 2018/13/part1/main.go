package main

import (
	"fmt"
	"io/ioutil"
	"os"
	"strings"
)

const (
	unset int = iota
	up
	down
	left
	right
	straight
	north
	east
	south
	west

	horizontal   = '-'
	vertical     = '|'
	intersection = '+'
	turn1        = '\\'
	turn2        = '/'
	collision    = 'X'
)

type cart struct {
	x                 int
	y                 int
	direction         int
	previousDirection int
}

func main() {
	content, _ := ioutil.ReadFile("../demo.txt")
	lines := strings.Split(string(content), "\n")

	// Our grid of tracks
	var grid map[int]map[int]rune
	grid = make(map[int]map[int]rune)

	var carts []*cart

	// scan the grid
	for row, line := range lines {
		for col, char := range line {
			// initialize the row if we haven't yet
			if ready := grid[row]; ready == nil {
				grid[row] = make(map[int]rune)
			}
			// Set into the grid
			grid[row][col] = char

			// find all the carts, replace them with an appropriate straight piece
			// We do this because it's easier to animate later
			direction := unset
			switch char {
			case 'v':
				direction = south
				grid[row][col] = '|'
			case '<':
				direction = west
				grid[row][col] = '-'
			case '^':
				direction = north
				grid[row][col] = '|'
			case '>':
				direction = east
				grid[row][col] = '-'
			default:
				continue
			}
			carts = append(carts, &cart{col, row, direction, unset})
		}
	}

	for {
		fmt.Println()
		printGrid(grid, carts)
		// @TODO: Carts have to move in a specific order, probably because multiple impacts can occur
		for _, cart := range carts {
			// move each cart until you impact
			switch cart.direction {
			case north:
				cart.y--
			case south:
				cart.y++
			case west:
				cart.x--
			case east:
				cart.x++
			}
			// decide if there's a direction change
			next := grid[cart.y][cart.x]

			//turn1        = '\\'
			//turn2        = '/'

			if next == turn1 || next == turn2 {
				switch cart.direction {
				case north:
					if next == turn1 {
						// \
						// |
						cart.direction = west
					} else {
						// /
						// |
						cart.direction = east
					}
				case south:
					if next == turn1 {
						// |
						// \
						cart.direction = east
					} else {
						// |
						// /
						cart.direction = west
					}
				case east:
					if next == turn1 {
						// -\
						cart.direction = south
					} else {
						// -/
						cart.direction = north
					}
				case west:
					if next == turn1 {
						// \-
						cart.direction = north
					} else {
						// /-
						cart.direction = south
					}
				}
			}

			/**
			* Each time a cart has the option to turn (by arriving at any intersection),
			* it turns left the first time, goes straight the second time, turns right
			* the third time, and then repeats those directions starting again with left
			* the fourth time, straight the fifth time, and so on. This process is
			* independent of the particular intersection at which the cart has arrived -
			* that is, the cart has no per-intersection memory.
			 */
			if next == intersection {

				switch cart.direction {
				case north:
					switch cart.previousDirection {
					// Never turned before or last made a right
					case unset, right:
						// turn left
						cart.direction = west
						cart.previousDirection = left

					// Last made a left
					case left:
						// do nothing
						cart.previousDirection = straight

					// Last went straight
					case straight:
						// turn right
						cart.direction = east
						cart.previousDirection = right
					}
				case south:
					switch cart.previousDirection {
					// Never turned before or last made a right
					case unset, right:
						// turn left
						cart.direction = east
						cart.previousDirection = left

					// Last made a left
					case left:
						// do nothing
						cart.previousDirection = straight

					// Last went straight
					case straight:
						// turn right
						cart.direction = west
						cart.previousDirection = right
					}
				case west:
					switch cart.previousDirection {
					// Never turned before or last made a right
					case unset, right:
						// turn left
						cart.direction = south
						cart.previousDirection = left

					// Last made a left
					case left:
						// do nothing
						cart.previousDirection = straight

					// Last went straight
					case straight:
						// turn right
						cart.direction = north
						cart.previousDirection = right
					}
				case east:
					switch cart.previousDirection {
					// Never turned before or last made a right
					case unset, right:
						// turn left
						cart.direction = north
						cart.previousDirection = left

					// Last made a left
					case left:
						// do nothing
						cart.previousDirection = straight

					// Last went straight
					case straight:
						// turn right
						cart.direction = south
						cart.previousDirection = right
					}
				}
			}
		}

		// See if any cart collided
		var coords map[string]bool
		coords = make(map[string]bool)
		for _, cart := range carts {
			// move each cart until you impact
			// decide if there's a direction change
			coord := fmt.Sprintf("%d,%d", cart.x, cart.y)
			if ok := coords[coord]; ok {
				fmt.Println()
				printGrid(grid, carts)
				fmt.Print("Collision at", coord)
				os.Exit(0)
			}
			coords[coord] = true
		}
	}
}

func printGrid(grid map[int]map[int]rune, carts []*cart) {
	var cgrid map[int]map[int]rune
	cgrid = make(map[int]map[int]rune)

	// Make copy so we don't modify grid
	for row := 0; row < len(grid); row++ {
		for col := 0; col < len(grid[row]); col++ {
			if ready := cgrid[row]; ready == nil {
				cgrid[row] = make(map[int]rune)
			}
			cgrid[row][col] = grid[row][col]
		}
	}

	for _, cart := range carts {
		// See if we already drew a cart, if so, mark as collision
		if cgrid[cart.y][cart.x] == '^' ||
			cgrid[cart.y][cart.x] == 'v' ||
			cgrid[cart.y][cart.x] == '<' ||
			cgrid[cart.y][cart.x] == '>' ||
			cgrid[cart.y][cart.x] == collision {
			cgrid[cart.y][cart.x] = collision
			continue
		}

		// Draw cart in copy grid
		switch cart.direction {
		case north:
			cgrid[cart.y][cart.x] = '^'
		case south:
			cgrid[cart.y][cart.x] = 'v'
		case west:
			cgrid[cart.y][cart.x] = '<'
		case east:
			cgrid[cart.y][cart.x] = '>'
		}
	}

	// Render grid, color carts and collisions as red
	for row := 0; row < len(cgrid); row++ {
		for col := 0; col < len(cgrid[row]); col++ {
			char := cgrid[row][col]
			switch char {
			case '^', 'v', '>', '<', 'X':
				fmt.Printf("\033[0;31m%s\033[0m", string(char))
			default:
				fmt.Print(string(char))
			}
		}
		fmt.Println()
	}

}

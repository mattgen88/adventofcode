package main

import (
	"fmt"
	"io/ioutil"
	"os"
	"sort"
	"strings"

	"github.com/davecgh/go-spew/spew"
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
	position          position
	direction         int
	previousDirection int
}

type position struct {
	x, y int
}

func main() {
	content, _ := ioutil.ReadFile("../input.txt")
	lines := strings.Split(string(content), "\n")

	// Our grid of tracks
	var grid map[position]rune
	grid = make(map[position]rune)

	var carts []*cart

	// scan the grid
	for row, line := range lines {
		for col, char := range line {
			p := position{col, row}
			// Set into the grid
			grid[p] = char

			// find all the carts, replace them with an appropriate straight piece
			// We do this because it's easier to animate later
			direction := unset
			switch char {
			case 'v':
				direction = south
				grid[p] = '|'
			case '<':
				direction = west
				grid[p] = '-'
			case '^':
				direction = north
				grid[p] = '|'
			case '>':
				direction = east
				grid[p] = '-'
			default:
				continue
			}
			carts = append(carts, &cart{p, direction, unset})
		}
	}

	for {
		fmt.Println()
		printGrid(grid, carts)
		// @TODO: Carts have to move in a specific order, probably because multiple impacts can occur
		sort.SliceStable(carts, func(a, b int) bool {
			if carts[a] == nil {
				return true
			}
			if carts[b] == nil {
				return false
			}
			if carts[a].position.x < carts[b].position.x {
				return true
			} else if carts[a].position.x > carts[b].position.x {
				return false
			}
			return carts[a].position.y < carts[b].position.y
		})

		for cartNum, cart := range carts {
			if cart == nil {
				continue
			}
			// move each cart until you impact
			switch cart.direction {
			case north:
				cart.position.y--
			case south:
				cart.position.y++
			case west:
				cart.position.x--
			case east:
				cart.position.x++
			}
			// decide if there's a direction change
			next := grid[cart.position]

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
			// See if any cart collided
			var coords map[position]bool
			coords = make(map[position]bool)
			newPosition := cart.position

			for i, cart := range carts {
				if cart == nil {
					continue
				}
				// move each cart until you impact
				// decide if there's a direction change
				if cart.position == newPosition && i != cartNum {
					fmt.Println()
					printGrid(grid, carts)
					fmt.Print("Collision at", cart.position)
					carts[i] = nil
					carts[cartNum] = nil
				}
				coords[cart.position] = true
			}
			count := 0
			for _, cart := range carts {
				if cart != nil {
					count++
				}
			}
			if count == 1 {
				spew.Dump(carts)
				os.Exit(0)
			}
		}

	}
}

func printGrid(grid map[position]rune, carts []*cart) {
	var cgrid map[position]rune
	cgrid = make(map[position]rune)

	// Make copy so we don't modify grid
	for p := range grid {
		cgrid[p] = grid[p]
	}

	for _, cart := range carts {
		if cart == nil {
			continue
		}
		// See if we already drew a cart, if so, mark as collision
		if cgrid[cart.position] == '^' ||
			cgrid[cart.position] == 'v' ||
			cgrid[cart.position] == '<' ||
			cgrid[cart.position] == '>' ||
			cgrid[cart.position] == collision {
			cgrid[cart.position] = collision
			continue
		}

		// Draw cart in copy grid
		switch cart.direction {
		case north:
			cgrid[cart.position] = '^'
		case south:
			cgrid[cart.position] = 'v'
		case west:
			cgrid[cart.position] = '<'
		case east:
			cgrid[cart.position] = '>'
		}
	}

	// Render grid, color carts and collisions as red
	var buf [][]string
	buf = make([][]string, 151)
	for i := 0; i < 151; i++ {
		buf[i] = make([]string, 151)
		buf[i][150] = "\n"
	}
	for p := range cgrid {
		char := cgrid[p]
		switch char {
		case '^', 'v', '>', '<', 'X':
			buf[p.y][p.x] = fmt.Sprintf("\033[0;31m%s\033[0m", string(char))
		default:
			buf[p.y][p.x] = fmt.Sprint(string(char))
		}
	}
	var buf2 []string
	for i := 0; i < 150; i++ {
		buf2 = append(buf2, fmt.Sprint(strings.Join(buf[i], "")))
	}
	fmt.Println(strings.Join(buf2, ""))

}

package main

import (
	"container/ring"
	"fmt"
)

const numPlayers = 479
const numMarbles = 7103500

// @TODO: Damn you wastl
// can't use dynamic resizing, too large to keep copying
// need to instead use a different data structure. Maybe doubly linked circular list

func main() {
	var scores map[int]int
	scores = make(map[int]int)

	var marbles []int
	for i := 1; i < numMarbles; i++ {
		marbles = append(marbles, i)
	}

	// game starts with marble 0 placed
	gameboard := ring.New(1)
	gameboard.Value = 0

	player := 0
	for {
		if len(marbles) == 0 {
			break
		}
		marble := marbles[0]
		marbles = marbles[1:]

		// check if marble % 23 == 0
		if marble > 0 && marble%23 == 0 {
			// Current player keeps marble, adds to score
			if _, ok := scores[player+1]; !ok {
				scores[player+1] = 0
			}
			scores[player+1] = scores[player+1] + marble
			// marble 8 marbles counter-clockwise is active, remove following marble, move to following marble

			gameboard = gameboard.Move(-8)
			r := gameboard.Unlink(1)

			value := r.Value.(int)
			gameboard = gameboard.Move(1)
			scores[player+1] = scores[player+1] + value
		} else {
			// move one over, insert, move to newly inserted marble
			gameboard = gameboard.Move(1)
			r := ring.New(1)
			r.Value = marble
			gameboard.Link(r)
			gameboard = gameboard.Move(1)
		}
		player = (player + 1) % numPlayers
	}
	winner := 0
	for _, s := range scores {
		if s > winner {
			winner = s
		}
	}
	fmt.Println(winner)
}

func print(r *ring.Ring) {
	r.Do(func(i interface{}) {
		if i.(int) == r.Value.(int) {
			fmt.Printf(" (%d)", i.(int))
		} else {
			fmt.Print(" ", i.(int))
		}
	})
	fmt.Println()
}

// insert at i, value x, into slice s
func insert(s []int, i, x int) []int {
	s = append(s, 0 /* use the zero value of the element type */)
	copy(s[i+1:], s[i:])
	s[i] = x
	return s
}

// cut element i out from slice s
func cut(s []int, i int) []int {
	j := i + 1
	copy(s[i:], s[j:])
	for k, n := len(s)-j+i, len(s); k < n; k++ {
		s[k] = 0 // or the zero value of T
	}
	s = s[:len(s)-j+i]
	return s
}

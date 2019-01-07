package main

import "fmt"

const numPlayers = 479
const numMarbles = 71035

func main() {
	var scores map[int]int
	scores = make(map[int]int)

	var marbles []int
	for i := 1; i < numMarbles; i++ {
		marbles = append(marbles, i)
	}

	// game starts with marble 0 placed
	var gameboard []int
	gameboard = append(gameboard, 0)

	currentMarbleIndex := 0
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
			// marble 7 marbles counter-clockwise is removed and added to score
			// currentIndex + len(goameBoard) - 7
			currentMarbleIndex = ((currentMarbleIndex + len(gameboard)) - 7) % len(gameboard)
			scores[player+1] = scores[player+1] + gameboard[currentMarbleIndex]
			gameboard = cut(gameboard, currentMarbleIndex)
			// marble immediately clockwise becomes current marble
		} else {
			// place lowest marble
			currentMarbleIndex = (currentMarbleIndex + 1) % len(gameboard)
			currentMarbleIndex = currentMarbleIndex + 1
			gameboard = insert(gameboard, currentMarbleIndex, marble)
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

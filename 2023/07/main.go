package main

import (
	"fmt"
	"sort"
	"strconv"
	"strings"

	utilities "github.com/mattgen88/adventofcode/2023"
)

const (
	HAND_TYPE_HIGH_CARD = iota
	HAND_TYPE_ONE_PAIR
	HAND_TYPE_TWO_PAIR
	HAND_TYPE_THREE_OF_A_KIND
	HAND_TYPE_FULL_HOUSE
	HAND_TYPE_FOUR_OF_A_KIND
	HAND_TYPE_FIVE_OF_A_KIND
)

var cards = map[rune]int{
	'2': 2,
	'3': 3,
	'4': 4,
	'5': 5,
	'6': 6,
	'7': 7,
	'8': 8,
	'9': 9,
	'T': 10,
	'J': 11,
	'Q': 12,
	'K': 13,
	'A': 14,
}

func main() {
	PartOne()
	PartTwo()
}

func PartOne() {
	fmt.Println("part 1")
	input := utilities.ReadInputSlice("input.txt")
	var hands Hands
	for _, row := range input {
		data := strings.Split(row, " ")
		bid, _ := strconv.Atoi(data[1])
		hands = append(hands, Hand{
			Cards: []rune(data[0]),
			Label: Label(data[0]),
			Bid:   bid,
		})
	}
	sort.Stable(hands)
	winnings := 0
	for rank, hand := range hands {
		winnings = winnings + ((rank + 1) * hand.Bid)
	}
	fmt.Printf("Winnings: %d\n", winnings)
}

type Hand struct {
	Cards []rune
	Label uint
	Bid   int
}
type Hands []Hand

func (h Hands) Len() int {
	return len(h)
}
func (h Hands) Less(i, j int) bool {
	if h[i].Label < h[j].Label {
		return true
	} else if h[i].Label > h[j].Label {
		return false
	}
	iCards := h[i].Cards
	jCards := h[j].Cards
	for it := 0; it < len(iCards); it++ {
		if cards[iCards[it]] < cards[jCards[it]] {
			return true
		}
		if cards[iCards[it]] > cards[jCards[it]] {
			return false
		}
	}
	// otherwise equal
	return false
}
func (h Hands) Swap(i, j int) {
	temp := h[i]
	h[i] = h[j]
	h[j] = temp
}

func Label(hand string) (label uint) {
	var counts = map[rune]int{}
	label = HAND_TYPE_HIGH_CARD
	for _, card := range hand {
		counts[card]++
		count := counts[card]
		switch {
		case count == 2:
			if label == HAND_TYPE_ONE_PAIR {
				label = HAND_TYPE_TWO_PAIR
			} else if label == HAND_TYPE_HIGH_CARD {
				label = HAND_TYPE_ONE_PAIR
			}

		case count == 3:
			label = HAND_TYPE_THREE_OF_A_KIND

		case count == 4:
			label = HAND_TYPE_FOUR_OF_A_KIND

		case count == 5:
			label = HAND_TYPE_FIVE_OF_A_KIND
		}
	}
	if IsFullHouse(hand) {
		label = HAND_TYPE_FULL_HOUSE
	}
	return label
}

func IsFullHouse(hand string) (predicate bool) {
	// full house contains a pair and a three of a kind
	var counts = map[rune]int{}
	for _, card := range hand {
		counts[card]++
	}
	// need 1 count of 3 and one count of 2
	hasTwo := false
	hasThree := false
	for _, count := range counts {
		if count == 2 {
			hasTwo = true
		}
		if count == 3 {
			hasThree = true
		}
	}
	return hasTwo && hasThree
}

var cards2 = map[rune]int{
	'2': 2,
	'3': 3,
	'4': 4,
	'5': 5,
	'6': 6,
	'7': 7,
	'8': 8,
	'9': 9,
	'T': 10,
	'J': 1,
	'Q': 12,
	'K': 13,
	'A': 14,
}

type Hands2 Hands

func (h Hands2) Len() int {
	return len(h)
}
func (h Hands2) Less(i, j int) bool {
	if h[i].Label < h[j].Label {
		return true
	} else if h[i].Label > h[j].Label {
		return false
	}
	iCards := h[i].Cards
	jCards := h[j].Cards
	for it := 0; it < len(iCards); it++ {
		if cards2[iCards[it]] < cards2[jCards[it]] {
			return true
		}
		if cards2[iCards[it]] > cards2[jCards[it]] {
			return false
		}
	}
	// otherwise equal
	return false
}
func (h Hands2) Swap(i, j int) {
	temp := h[i]
	h[i] = h[j]
	h[j] = temp
}
func Label2(hand string) (label uint) {
	var counts = map[rune]int{}
	for _, card := range hand {
		counts[card]++
	}

	switch true {
	case IsFiveOfKind(counts):
		return HAND_TYPE_FIVE_OF_A_KIND
	case IsFourOfKind(counts):
		return HAND_TYPE_FOUR_OF_A_KIND
	case IsFullHouse2(counts):
		return HAND_TYPE_FULL_HOUSE
	case IsThreeOfKind(counts):
		return HAND_TYPE_THREE_OF_A_KIND
	case IsTwoPair(counts):
		return HAND_TYPE_TWO_PAIR
	case IsOnePair(counts):
		return HAND_TYPE_ONE_PAIR
	default:
		return HAND_TYPE_HIGH_CARD
	}
}

func IsFiveOfKind(hand map[rune]int) bool {
	// 5 j
	// 4 j
	// 3 j and a pair
	// 2 j and three of a kind
	// 1 j and four of a kind
	if hand['J'] >= 4 {
		return true
	}
	hasPair := false
	hasThree := false
	hasFour := false
	hasFive := false
	for _, c := range hand {
		if c == 2 {
			hasPair = true
		}
		if c == 3 {
			hasThree = true
		}
		if c == 4 {
			hasFour = true
		}
		if c == 5 {
			hasFive = true
		}
	}

	return (hasPair && hand['J'] == 3) || (hasThree && hand['J'] == 2) || (hasFour && hand['J'] == 1) || hasFive
}
func IsFourOfKind(hand map[rune]int) bool {
	// assumes we already checked for 5 of a kind
	// 3 j
	// 2 j and a pair
	// 1 j and three of a kind
	// 4 of a kind
	if hand['J'] == 3 {
		return true
	}
	hasPair := false
	hasThree := false
	hasFour := false
	for r, c := range hand {
		if c == 2 && r != 'J' {
			hasPair = true
		}
		if c == 3 {
			hasThree = true
		}
		if c == 4 {
			hasFour = true
		}
	}

	return (hasThree && hand['J'] == 1) || (hasPair && hand['J'] == 2) || hasFour
}

func IsFullHouse2(hand map[rune]int) bool {
	// assumes we already checked for 4 of a kind
	// 2 pair and a J
	// or 1 pair and 3 of a kind
	hasPair := false
	hasTwoPair := false
	hasThree := false
	for _, c := range hand {
		if c == 2 && hasPair {
			hasTwoPair = true
		}
		if c == 2 && !hasPair {
			hasPair = true
		}
		if c == 3 {
			hasThree = true
		}
	}

	return (hasTwoPair && hand['J'] == 1) || (hasThree && hasPair)
}

func IsThreeOfKind(hand map[rune]int) bool {
	// assumes we already checked for full house
	// has 1 j and a pair
	// has 3 of a kind
	// has 2 j
	if hand['J'] == 2 {
		return true
	}
	hasPair := false
	hasThree := false
	for _, c := range hand {
		if c == 2 {
			hasPair = true
		}
		if c == 3 {
			hasThree = true
		}
	}

	return (hasPair && hand['J'] == 1) || hasThree
}

func IsTwoPair(hand map[rune]int) bool {
	// assumes we already checked for 3 of a kind
	// has two pairs
	hasOnePair := false
	hasTwoPair := false
	for _, c := range hand {
		if c == 2 && hasOnePair {
			hasTwoPair = true
		}
		if c == 2 && !hasOnePair {
			hasOnePair = true
		}
	}
	return hasOnePair && hasTwoPair
}

func IsOnePair(hand map[rune]int) bool {
	// asumes we already checked for two pairs
	// has a J
	// has a pair
	if hand['J'] == 1 {
		return true
	}
	hasOnePair := false
	for _, c := range hand {
		if c == 2 {
			hasOnePair = true
		}
	}
	return hasOnePair
}

func PartTwo() {
	fmt.Println("part 2")
	input := utilities.ReadInputSlice("input.txt")
	var hands Hands2
	for _, row := range input {
		data := strings.Split(row, " ")
		bid, _ := strconv.Atoi(data[1])
		hands = append(hands, Hand{
			Cards: []rune(data[0]),
			Label: Label2(data[0]),
			Bid:   bid,
		})
	}
	sort.Stable(hands)
	winnings := 0
	for rank, hand := range hands {
		fmt.Printf("Hand %s type %s Rank %d\n", string(hand.Cards), typeToString(hand.Label), rank)
		winnings = winnings + ((rank + 1) * hand.Bid)
	}
	fmt.Printf("Winnings: %d\n", winnings)
}

func typeToString(t uint) string {
	switch t {
	case HAND_TYPE_FIVE_OF_A_KIND:
		return "Five of a kind"
	case HAND_TYPE_FOUR_OF_A_KIND:
		return "Four of a kind"
	case HAND_TYPE_FULL_HOUSE:
		return "Full house"
	case HAND_TYPE_HIGH_CARD:
		return "High card"
	case HAND_TYPE_ONE_PAIR:
		return "One pair"
	case HAND_TYPE_THREE_OF_A_KIND:
		return "Three of a kind"
	case HAND_TYPE_TWO_PAIR:
		return "Two pair"
	default:
		return "Unknown"
	}
}

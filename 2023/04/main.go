package main

import (
	"fmt"
	"regexp"
	"strings"
	"sync"

	utilities "github.com/mattgen88/adventofcode/2023"
)

func main() {
	PartOne()
	PartTwo()
}

type lottoCard struct {
	cardNumbers    []string
	winningNumbers []string
}

func PartOne() {
	input := utilities.ReadInputSlice("input.txt")
	re := regexp.MustCompile(`\s+`)
	cards := make(chan lottoCard)
	var wg sync.WaitGroup
	mu := &sync.Mutex{}
	sum := 0
	for i := 0; i < 12; i++ {
		wg.Add(1)
		go func() {
			defer wg.Done()
			for data := range cards {
				// value read
				points := calculatePointsPartOne(data.cardNumbers, data.winningNumbers)
				mu.Lock()
				sum = sum + points
				mu.Unlock()
			}
		}()
	}
	for _, line := range input {
		cardData := strings.Split(strings.Split(line, ": ")[1], "|")
		cardNumbers := re.Split(cardData[0], -1)
		winningNumbers := re.Split(cardData[1], -1)
		cards <- lottoCard{cardNumbers: cardNumbers, winningNumbers: winningNumbers}
	}
	close(cards)
	wg.Wait()
	fmt.Printf("Sum: %d\n", sum)
}

func calculatePointsPartOne(cardNumbers, winningNumbers []string) int {
	points := 0
	for _, cardNumber := range cardNumbers {
		if cardNumber == "" {
			continue
		}
		for _, winningNumber := range winningNumbers {
			if winningNumber == "" {
				continue
			}
			if cardNumber == winningNumber {
				if points == 0 {
					points = 1
				} else {
					points = points * 2
				}
			}
		}
	}
	return points
}

func calculatePointsPartTwo(cardNumbers, winningNumbers []string) int {
	matches := 0
	for _, cardNumber := range cardNumbers {
		if cardNumber == "" {
			continue
		}
		for _, winningNumber := range winningNumbers {
			if winningNumber == "" {
				continue
			}
			if cardNumber == winningNumber {
				matches++
			}
		}
	}
	return matches
}
func PartTwo() {
	input := utilities.ReadInputSlice("input.txt")
	re := regexp.MustCompile(`\s+`)

	cardCount := make(map[int]int)
	for i := 0; i < len(input); i++ {
		cardCount[i] = 1
	}

	for cardIndex, line := range input {
		cardData := strings.Split(strings.Split(line, ": ")[1], "|")
		cardNumbers := re.Split(cardData[0], -1)
		winningNumbers := re.Split(cardData[1], -1)
		matches := calculatePointsPartTwo(cardNumbers, winningNumbers)
		count := 1
		if _, exists := cardCount[cardIndex]; exists {
			count = cardCount[cardIndex]
		}
		for n := count; n > 0; n-- {
			for i := cardIndex + 1; i <= matches+cardIndex; i++ {
				if _, exists := cardCount[i]; exists {
					cardCount[i]++
				}
			}
		}
	}
	cards := 0
	for _, v := range cardCount {
		cards = cards + v
	}
	fmt.Printf("Sum: %d\n", cards)
}

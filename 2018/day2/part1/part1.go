package main

import (
	"fmt"
	"io/ioutil"
	"strings"
)

func main() {
	content, _ := ioutil.ReadFile("../input.txt")
	lines := strings.Split(string(content), "\n")
	threes := 0
	twos := 0
	for _, line := range lines {
		fmt.Println(line)
		var vals map[rune]int
		vals = make(map[rune]int)
		for _, char := range line {
			vals[char]++
		}
		for char, val := range vals {
			if val == 2 {
				fmt.Printf("Two %s found\n", string(char))
				twos++
				break
			}
		}
		for char, val := range vals {
			if val == 3 {
				fmt.Printf("Three %s found\n", string(char))
				threes++
				break
			}
		}
	}
	fmt.Printf("Threes: %d Twos: %d Hash: %d\n", threes, twos, threes*twos)
}

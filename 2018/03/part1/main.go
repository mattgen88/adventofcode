package main

import (
	"fmt"
	"io/ioutil"
	"regexp"
	"strconv"
	"strings"
)

func main() {
	content, _ := ioutil.ReadFile("../input.txt")
	lines := strings.Split(string(content), "\n")
	re := regexp.MustCompile(`^#(\d+) @ (\d+),(\d+): (\d+)x(\d+)$`)
	var fabric map[int]map[int]int
	fabric = make(map[int]map[int]int)
	for i := 0; i < 1000; i++ {
		fabric[i] = make(map[int]int)
	}
	for _, line := range lines {
		fmt.Println(line)
		matches := re.FindStringSubmatch(line)
		id, _ := strconv.Atoi(matches[1])
		x, _ := strconv.Atoi(matches[2])
		y, _ := strconv.Atoi(matches[3])
		n, _ := strconv.Atoi(matches[4])
		m, _ := strconv.Atoi(matches[5])
		fmt.Printf("ID: %d, X: %d, Y: %d, N: %d, M: %d\n", id, x, y, n, m)
		for i := x; i < x+n; i++ {
			for j := y; j < y+m; j++ {
				fabric[i][j]++
			}
		}
	}
	area := 0
	for i := 0; i < 1000; i++ {
		for j := 0; j < 1000; j++ {
			//fmt.Print(fabric[i][j])
			if fabric[i][j] > 1 {
				area++
			}
		}
		//fmt.Println()
	}
	fmt.Println("Total area: ", area)
}

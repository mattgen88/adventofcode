package main

import (
	"io/ioutil"
	"log"
	"os"
	"strconv"
	"strings"
)

func main() {
	var frequencies map[int]bool
	frequencies = make(map[int]bool)

	content, _ := ioutil.ReadFile("../input.txt")
	lines := strings.Split(string(content), "\n")

	frequency := 0
	for {
		for _, line := range lines {
			change, _ := strconv.Atoi(line)
			frequency = frequency + change
			if _, ok := frequencies[frequency]; ok {
				log.Println("Repeated frequency: ", frequency)
				os.Exit(0)
			}
			frequencies[frequency] = true
		}
	}
}

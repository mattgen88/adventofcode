package main

import (
	"io/ioutil"
	"log"
	"strconv"
	"strings"
)

func main() {
	content, _ := ioutil.ReadFile("../input.txt")
	lines := strings.Split(string(content), "\n")
	frequency := 0
	for _, line := range lines {
		change, _ := strconv.Atoi(line)
		frequency = frequency + change
	}
	log.Println(frequency)
}

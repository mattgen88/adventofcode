package main

import (
	"fmt"
	"io/ioutil"
	"strconv"
	"strings"
)

func main() {
	content, _ := ioutil.ReadFile("../input.txt")
	//content, _ := ioutil.ReadFile("../demo.txt")

	fields := strings.Split(string(content), " ")
	var data []int
	for _, field := range fields {
		num, _ := strconv.Atoi(field)
		data = append(data, num)
	}
	_, value := unpack(data)
	fmt.Println(value)
}

// returns remaining items unprocessed, value
func unpack(data []int) ([]int, int) {
	children := data[0]
	metadataCount := data[1]
	data = data[2:]
	var values []int
	// For each of the chlidren, if there are any
	for i := 0; i < children; i++ {
		var childValue int
		data, childValue = unpack(data)
		values = append(values, childValue)
	}
	value := 0
	if children == 0 {
		value = sum(data[:metadataCount])
	} else {
		for _, v := range data[:metadataCount] {
			// skip child nodes that don't exist (out of bounds of values)
			if v > 0 && v <= len(values) {
				value += values[v-1]
			}
		}
	}
	return data[metadataCount:], value
}

func sum(slice []int) int {
	total := 0
	for _, v := range slice {
		total += v
	}
	return total
}

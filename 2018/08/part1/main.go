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
	metadata, _ := unpack(data)
	fmt.Println(sum(metadata))
}

// returns extracted metadata, remaining items unprocessed
func unpack(data []int) ([]int, []int) {
	children := data[0]
	metadataCount := data[1]
	data = data[2:]
	var metadata []int
	// For each of the chlidren, if there are any
	for i := 0; i < children; i++ {
		var childMetadata []int
		childMetadata, data = unpack(data)
		metadata = append(metadata, childMetadata...)
	}
	metadata = append(metadata, data[:metadataCount]...)
	return metadata, data[metadataCount:]
}

func sum(slice []int) int {
	total := 0
	for _, v := range slice {
		total += v
	}
	return total
}

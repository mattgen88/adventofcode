package main

import (
	"fmt"
	"io/ioutil"
)

func main() {
	content, _ := ioutil.ReadFile("../input.txt")
	shrunk := shrink(content)
	fmt.Printf("Final length: %d\n", len(shrunk))
}

func shrink(polymer []byte) []byte {

	var newPolymer []byte

	var antiParticle byte
	// collapse polymer
	for i := 0; i < len(polymer); i++ {

		currentParticle := polymer[i]
		var nextParticle byte
		if i+1 < len(polymer) {
			nextParticle = polymer[i+1]
		} else {
			// can't continue
			newPolymer = append(newPolymer, polymer[i])
			break
		}

		// look at next char
		if int(currentParticle) < 97 {
			antiParticle = currentParticle + 32
		}
		if int(currentParticle) >= 97 {
			antiParticle = currentParticle - 32
		}
		if antiParticle == nextParticle {
			// collapse and skip
			i++
		} else {
			newPolymer = append(newPolymer, polymer[i])
		}
	}

	if len(newPolymer) == len(polymer) {
		return polymer
	}
	return shrink(newPolymer)
}

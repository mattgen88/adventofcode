package main

import (
	"fmt"
	"io/ioutil"
	"os"
	"strings"
)

func main() {
	content, _ := ioutil.ReadFile("../input.txt")
	lines := strings.Split(string(content), "\n")
	for _, line := range lines {
		for _, otherline := range lines {
			differences := 0
			same := ""
			for charAt := range line {
				if line[charAt] != otherline[charAt] {
					differences++
				} else {
					same = fmt.Sprintf("%s%s", same, string(line[charAt]))
				}
			}
			if differences == 1 {
				fmt.Printf("%s\n%s\n", line, otherline)
				fmt.Println(same)
				os.Exit(0)
			}
		}
	}
}

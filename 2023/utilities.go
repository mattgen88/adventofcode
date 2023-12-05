package utilities

import (
	"os"
	"strings"
)

func ReadInputSlice(file string) []string {
	h, err := os.ReadFile(file)
	if err != nil {
		panic(err)
	}
	return strings.Split(string(h), "\n")
}

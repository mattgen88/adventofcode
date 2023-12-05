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

func ReadInputSliceAndDo(file string, f func(line string)) {
	input := ReadInputSlice(file)
	for _, line := range input {
		f(line)
	}
}

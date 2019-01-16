package main

import (
	"fmt"
	"io/ioutil"
	"strings"
)

type garden struct {
	pots     []bool
	zero     int
	potcount int
	rules    [][]bool
}

func (g *garden) Tick() {
	// check each rule against the current position of pots
	var newPots []bool
	newPots = make([]bool, len(g.pots))
	newPotsOffset := 0
	for i := 0; i < len(g.pots); i++ {
		for _, rule := range g.rules {
			if g.GetPot(i-2) == rule[0] && g.GetPot(i-1) == rule[1] && g.GetPot(i) == rule[2] && g.GetPot(i+1) == rule[3] && g.GetPot(i+2) == rule[4] {
				newPots[i+newPotsOffset] = true
				if i-2 < 0 {
					// prepend to the new pots a false value
					// incrase zero
					g.zero++
					newPotsOffset++
					newPots = append([]bool{false}, newPots...)
				}
				if i > len(g.pots)-2 {
					// append to the new pots a false value
					newPots = append(newPots, false)
				}
			}
		}
	}
	g.pots = newPots
}

func (g *garden) GetPot(i int) bool {
	if i >= len(g.pots) || i < 0 {
		return false
	}
	return g.pots[i]
}

func (g *garden) ToString() {
	for i := range g.pots {
		if g.pots[i] {
			fmt.Print("#")
		} else {
			fmt.Print(".")
		}
	}
	fmt.Println()

}

func (g *garden) GetSum() int {
	sum := 0
	for i := range g.pots {
		if g.pots[i] {
			sum = sum + (i - g.zero)
		}
	}
	return sum
}

func main() {
	content, _ := ioutil.ReadFile("../input.txt")
	lines := strings.Split(string(content), "\n")

	initialState := lines[0][15:]
	rules := lines[2:]

	g := stringToGarden(initialState, rules)
	for i := 0; i < 20; i++ {
		g.Tick()
	}
	fmt.Printf("Sum: %d\n", g.GetSum())
}

func stringToGarden(s string, rules []string) *garden {
	g := &garden{}
	g.pots = make([]bool, len(s)+6)
	g.potcount = len(s)
	g.zero = 3
	for i, p := range s {
		g.pots[i+3] = p == '#'
	}
	for i := range rules {
		rule := stringToRule(rules[i])
		if rule != nil {
			g.rules = append(g.rules, stringToRule(rules[i]))
		}
	}
	return g
}

func stringToRule(s string) []bool {
	fmt.Println(s)
	if s[9] != '#' {
		return nil
	}
	var rule []bool
	rule = make([]bool, 5)
	for i, v := range s[:5] {
		rule[i] = v == '#'
	}
	return rule
}

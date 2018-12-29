package main

import (
	"fmt"
	"io/ioutil"
	"regexp"
	"sort"
	"strings"
)

type step struct {
	Dependencies []string
	Dependent    []string
	Name         string
}

func main() {
	content, _ := ioutil.ReadFile("../input.txt")
	//content, _ := ioutil.ReadFile("../demo.txt")
	lines := strings.Split(strings.Trim(string(content), "\n"), "\n")
	re := regexp.MustCompile(`Step (.) must be finished before step (.) can begin.`)
	var steps map[string]*step
	steps = make(map[string]*step)
	for _, line := range lines {
		matches := re.FindStringSubmatch(line)
		parentName := matches[1]
		childName := matches[2]
		var parentNode *step
		var childNode *step

		// Create new node or find referenced node
		if n, ok := steps[parentName]; ok {
			parentNode = n
		} else {
			parentNode = &step{Name: parentName}
			steps[parentName] = parentNode
		}

		// Create new node or find referenced node
		if n, ok := steps[childName]; ok {
			childNode = n
		} else {
			childNode = &step{Name: childName}
			steps[childName] = childNode
		}
		// Set the child's parent
		childNode.Dependencies = append(childNode.Dependencies, parentNode.Name)
		// Set the parent's child
		parentNode.Dependent = append(parentNode.Dependent, childNode.Name)
	}

	var incomplete []string
	for i := range steps {
		incomplete = append(incomplete, i)
	}
	var complete []string
	traverse(steps, nil, complete, incomplete)
	fmt.Println()
}

func traverse(steps map[string]*step, n *step, completed, incomplete []string) {

	// If we aren't given a step, find first available
	if n == nil {
		available := getAvailable(steps, completed, incomplete)
		n = steps[available[0]]
	}

	// step can be completed because all parents are complete
	fmt.Print(n.Name)
	completed = append(completed, n.Name)

	// remove from incomplete
	for i, v := range incomplete {
		if n.Name == v {
			incomplete = append(incomplete[:i], incomplete[i+1:]...)
		}
	}

	// Find available steps
	available := getAvailable(steps, completed, incomplete)
	if len(available) == 0 {
		// We're done, exit
		return
	}

	// Not done, get next available work
	traverse(steps, steps[available[0]], completed, incomplete)

}

// Creates an alphabetized list of steps available to be worked on based on complete and incomplete
func getAvailable(steps map[string]*step, completed, incomplete []string) []string {
	var available []string
	for _, v := range incomplete {
		if isReady(steps[v], completed) {
			available = append(available, v)
		}
	}
	sort.Strings(available)
	return available
}

// Check that all parent steps are complete
func isReady(n *step, completed []string) bool {
	sort.Strings(n.Dependencies)
	for _, dependency := range n.Dependencies {
		// Check that each dependency is in the completed list
		complete := false
		for _, c := range completed {
			if c == dependency {
				complete = true
			}
		}

		// Dependency was found in completed
		if !complete {
			return false
		}
	}
	return true
}

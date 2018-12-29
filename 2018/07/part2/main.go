package main

import (
	"fmt"
	"io/ioutil"
	"regexp"
	"sort"
	"strings"
	"sync"
	"time"
)

const NumWorkers = 5
const BaseCost = 60

type step struct {
	Dependencies []string
	Dependent    []string
	Name         string
	Cost         int
}
type elf struct{}

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
			cost := int(parentName[0]) - 64
			parentNode = &step{Name: parentName, Cost: cost}
			steps[parentName] = parentNode
		}

		// Create new node or find referenced node
		if n, ok := steps[childName]; ok {
			childNode = n
		} else {
			cost := int(childName[0]) - 64
			childNode = &step{Name: childName, Cost: cost}
			steps[childName] = childNode
		}
		// Set the child's parent
		childNode.Dependencies = append(childNode.Dependencies, parentNode.Name)
		// Set the parent's child
		parentNode.Dependent = append(parentNode.Dependent, childNode.Name)
	}

	var incomplete []string
	var complete []string
	mutex := &sync.Mutex{}
	for i := range steps {
		incomplete = append(incomplete, i)
	}
	sort.Strings(incomplete)

	// Will tick every second
	ticker := time.NewTicker(time.Millisecond * 10)
	defer ticker.Stop()

	// limited number of elf workers pulls off work from available list
	// elf worker will wait for cost amount of time in ticks, while it does the work
	// when it wakes up, it'll return to the pool of available workers
	// exit condition is all steps complete (all in progress and incomplete work is empty
	var next *step
	inprogress := make(chan *step, NumWorkers)
	seconds := 0
	go func() {
		for {
			<-ticker.C
			seconds++
			fmt.Println(seconds)
		}
	}()
	for len(complete) < len(steps) {
		// allocates an elf
		mutex.Lock()
		next, incomplete, complete = getNextAvailable(steps, inprogress, incomplete, complete)
		mutex.Unlock()
		if next != nil {
			go func() {
				next := <-inprogress
				fmt.Println("Elf working on", next.Name, "for", next.Cost+BaseCost, "seconds at", seconds)
				// @TODO: Wait for so many ticks using a clock
				for i := next.Cost + BaseCost; i > 0; i-- {
					<-ticker.C
				}

				// elf is done with work, update lists
				mutex.Lock()

				// add to complete
				complete = append(complete, next.Name)

				// Frees the elf
				mutex.Unlock()
				fmt.Println("Elf working on", next.Name, "done")
			}()
		}
	}
	fmt.Println(strings.Join(complete, ""))
}

func getNextAvailable(work map[string]*step, inprogress chan *step, incomplete, complete []string) (*step, []string, []string) {
	sort.Strings(incomplete)
	for i, v := range incomplete {
		next := work[v]
		if isReady(next, complete) {
			// remove from incomplete
			incomplete = append(incomplete[:i], incomplete[i+1:]...)
			// add to inprogress
			inprogress <- next
			return next, incomplete, complete
		}
	}
	return nil, incomplete, complete
}

// Check that all parent steps are complete
func isReady(n *step, complete []string) bool {
	sort.Strings(n.Dependencies)
	for _, dependency := range n.Dependencies {
		// Check that each dependency is in the completed list
		isComplete := false
		for _, c := range complete {
			if c == dependency {
				isComplete = true
			}
		}

		// Dependency was found in completed
		if !isComplete {
			return false
		}
	}
	return true
}

package main

import (
	"fmt"
	"io/ioutil"
	"os"
	"regexp"
	"strconv"
	"strings"
)

type GridPoint interface {
	GetX() int
	GetY() int
	Print()
	Tick()
}

type MovingPoint struct {
	*Point
	VelocityX int
	VelocityY int
}

func (p *MovingPoint) Tick() {
	p.X = p.GetX() + p.VelocityX
	p.Y = p.GetY() + p.VelocityY
}

type Point struct {
	X     int
	Y     int
	Value interface{}
}

func (p *Point) Print() {
	if p.Value.(bool) {
		fmt.Print("#")
	} else {
		fmt.Print(".")
	}
}

func (p *Point) GetX() int {
	return p.X
}

func (p *Point) GetY() int {
	return p.Y
}

type Grid struct {
	g    map[int]map[int]GridPoint
	minX int
	maxX int
	minY int
	maxY int
}

func NewGrid(minX, maxX, minY, maxY int) Grid {
	g := Grid{make(map[int]map[int]GridPoint), minX, maxX, minY, maxY}

	for x := minX; x <= maxX; x++ {
		g.g[x] = make(map[int]GridPoint)
	}
	return g
}

func (g Grid) AddPoint(p GridPoint) {
	if p.GetX() > g.maxX || p.GetX() < g.minX || p.GetY() > g.maxY || p.GetY() < g.minY {
		return
	}
	g.g[p.GetX()][p.GetY()] = p
}

func (g Grid) Print() {
	for row := g.minY; row <= g.maxY; row++ {
		for col := g.minX; col <= g.maxX; col++ {
			if _, ok := g.g[col][row]; ok {
				g.g[col][row].Print()
			} else {
				fmt.Print(".")
			}
		}
		fmt.Println()
	}
}

func main() {
	content, _ := ioutil.ReadFile("../input.txt")
	lines := strings.Split(string(content), "\n")
	re := regexp.MustCompile(`position=<\s*?([-\d]+),\s*([-\d]+)> velocity=<\s*?([-\d]+),\s*([-\d]+)>`)
	var points []GridPoint
	var minX, minY, maxX, maxY int
	for _, line := range lines {
		m := re.FindStringSubmatch(line)
		posX, _ := strconv.Atoi(m[1])
		posY, _ := strconv.Atoi(m[2])
		velX, _ := strconv.Atoi(m[3])
		velY, _ := strconv.Atoi(m[4])
		p := &MovingPoint{&Point{posX, posY, true}, velX, velY}
		points = append(points, p)
	}
	// fastforward time a bit
	// started with 10, then 100... so on before i saw the image converging
	seconds := 10000
	viewable := false
	for _, p := range points {
		for i := 0; i < 10000; i++ {
			p.Tick()
		}
	}
	for {
		seconds++
		// absurd numbers, should be much larger than necessary. Allows the gird to only be as big as necessary for the points
		minX = 999999
		minY = 999999
		maxX = -999999
		maxY = -999999
		for _, p := range points {
			p.Tick()
			if p.GetX() < minX {
				minX = p.GetX()
			}
			if p.GetX() > maxX {
				maxX = p.GetX()
			}
			if p.GetY() < minY {
				minY = p.GetY()
			}
			if p.GetY() > maxY {
				maxY = p.GetY()
			}
		}
		fmt.Printf("Making a grid that is from %d to %d, by %d to %d at seconds %d\n", minX, maxX, minY, maxY, seconds)
		g := NewGrid(minX, maxX, minY, maxY)
		for _, p := range points {
			g.AddPoint(p)
		}
		// Only print when the grid is actually viewable
		if len(g.g) < 100 {
			viewable = true
			g.Print()
		}
		if len(g.g) > 100 && viewable {
			os.Exit(0)
		}
	}
}

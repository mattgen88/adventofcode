package main

import (
	"math"
	"strconv"
	"strings"

	"github.com/davecgh/go-spew/spew"
	utilities "github.com/mattgen88/adventofcode/2023"
)

func main() {
	PartOne()
	PartTwo()
}

type farmMap struct {
	soil        int
	fertilizer  int
	water       int
	light       int
	temperature int
	humidity    int
	location    int
}

func newFarmMap(id int) *farmMap {
	return &farmMap{
		id,
		id,
		id,
		id,
		id,
		id,
		id,
	}
}

func initialize(m *map[int]*farmMap, len int) *map[int]*farmMap {
	for i := 0; i < len; i++ {
		farmMap := newFarmMap(i)
		(*m)[i] = farmMap
	}
	return m
}

func PartOne() {
	input := utilities.ReadInput("input.txt")
	sections := strings.Split(input, "\n\n")
	seeds := sections[0]
	_ = seeds
	seedsToSoilMap := parseMap(sections[1])
	soilToFertilizerMap := parseMap(sections[2])
	fertilizerToWaterMap := parseMap(sections[3])
	waterToLightMap := parseMap(sections[4])
	lightToTemperaturMap := parseMap(sections[5])
	temperatureToHumidityMap := parseMap(sections[6])
	humidityToLocationMap := parseMap(sections[7])
	max := math.Max(getMax(seedsToSoilMap), getMax(soilToFertilizerMap))
	max = math.Max(max, getMax(fertilizerToWaterMap))
	max = math.Max(max, getMax(waterToLightMap))
	max = math.Max(max, getMax(lightToTemperaturMap))
	max = math.Max(max, getMax(temperatureToHumidityMap))
	max = math.Max(max, getMax(humidityToLocationMap))
	spew.Dump(int(max))
	// we need a map of seed to an array of
	// soil, fertilizer, water, light, temperature, humidity, location
	m := make(map[int]*farmMap, int(max))
	m = *initialize(&m, int(max))
}

func parseMap(s string) (data [][]float64) {
	rows := strings.Split(strings.SplitN(s, "\n", 2)[1], "\n")
	for _, row := range rows {
		var r []float64
		for _, s := range strings.Split(row, " ") {
			i, _ := strconv.Atoi(s)
			r = append(r, float64(i))
		}
		data = append(data, r)
	}
	return
}
func getMax(data [][]float64) float64 {
	max := float64(0)
	for _, row := range data {
		max = math.Max(max, math.Max(row[0]+row[2], row[1]+row[2]))
	}
	return max
}
func PartTwo() {
}

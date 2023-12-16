package main

import (
	"fmt"
	"math"
	"strconv"
	"strings"
	"sync"

	utilities "github.com/mattgen88/adventofcode/2023"
)

func main() {
	PartOne()
	PartTwo()
}

func PartOne() {
	input := utilities.ReadInput("input.txt")
	sections := strings.Split(input, "\n\n")
	seeds := parseSeeds(sections[0])
	seedsToSoilMap := parseMap(sections[1])
	soilToFertilizerMap := parseMap(sections[2])
	fertilizerToWaterMap := parseMap(sections[3])
	waterToLightMap := parseMap(sections[4])
	lightToTemperaturMap := parseMap(sections[5])
	temperatureToHumidityMap := parseMap(sections[6])
	humidityToLocationMap := parseMap(sections[7])

	var closest float64
	closest = math.Inf(1)
	for _, seed := range seeds {
		soilLocation := mappedValueOrDefault(seed, seedsToSoilMap)
		fertilization := mappedValueOrDefault(soilLocation, soilToFertilizerMap)
		water := mappedValueOrDefault(fertilization, fertilizerToWaterMap)
		light := mappedValueOrDefault(water, waterToLightMap)
		temperature := mappedValueOrDefault(light, lightToTemperaturMap)
		humidity := mappedValueOrDefault(temperature, temperatureToHumidityMap)
		location := mappedValueOrDefault(humidity, humidityToLocationMap)
		closest = math.Min(closest, location)
	}
	fmt.Printf("Closest is %f\n", closest)
}

func mappedValueOrDefault(seed float64, m [][]float64) (result float64) {
	result = seed
	for _, row := range m {
		// source, dest, length
		destination := row[0]
		source := row[1]
		length := row[2]
		if seed >= source && seed <= source+length {
			// d is mapped, figure out to what value
			result = destination + (seed - source)
			return
		}
	}
	return
}

func parseSeeds(s string) (seeds []float64) {
	s = strings.Replace(s, "seeds: ", "", 1)
	for _, seed := range strings.Split(s, " ") {
		seedVal, _ := strconv.Atoi(seed)
		seeds = append(seeds, float64(seedVal))
	}
	return
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
func PartTwo() {
	input := utilities.ReadInput("input.txt")
	sections := strings.Split(input, "\n\n")
	seeds := parseSeeds(sections[0])
	seedsToSoilMap := parseMap(sections[1])
	soilToFertilizerMap := parseMap(sections[2])
	fertilizerToWaterMap := parseMap(sections[3])
	waterToLightMap := parseMap(sections[4])
	lightToTemperaturMap := parseMap(sections[5])
	temperatureToHumidityMap := parseMap(sections[6])
	humidityToLocationMap := parseMap(sections[7])

	seedData := make(chan []float64)
	var wg sync.WaitGroup
	mu := &sync.Mutex{}
	closest := math.Inf(1)
	for i := 0; i < len(seeds)/2; i++ {
		wg.Add(1)
		go func() {
			fmt.Println("Starting thread")
			defer wg.Done()
			for seed := range seedData {
				// return min location
				start := seed[0]
				length := seed[1]
				fmt.Printf("Calculating location of seeds %f through %f\n", start, start+length)
				for i := start; i < start+length; i++ {
					soilLocation := mappedValueOrDefault(i, seedsToSoilMap)
					fertilization := mappedValueOrDefault(soilLocation, soilToFertilizerMap)
					water := mappedValueOrDefault(fertilization, fertilizerToWaterMap)
					light := mappedValueOrDefault(water, waterToLightMap)
					temperature := mappedValueOrDefault(light, lightToTemperaturMap)
					humidity := mappedValueOrDefault(temperature, temperatureToHumidityMap)
					location := mappedValueOrDefault(humidity, humidityToLocationMap)
					mu.Lock()
					closest = math.Min(closest, location)
					mu.Unlock()
				}
			}
		}()
	}
	for i := 0; i < len(seeds); {
		seedData <- []float64{seeds[i], seeds[i+1]}
		i = i + 2
	}
	close(seedData)
	wg.Wait()
	fmt.Printf("Closest: %f\n", closest)

}

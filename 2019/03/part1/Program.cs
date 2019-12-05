#nullable enable
using static System.Console;
using System.IO;
using static System.Convert;
using System.Collections.Generic;
using System;
using System.Linq;


namespace part1
{
    class Program
    {
        static void Main(string[] args)
        {
            string? line;
            List<List<string>> wires = new List<List<string>>();

            using (StreamReader file = new StreamReader(@"../input.txt")) {

                while ((line = file.ReadLine()) != null)
                {
                    wires.Add(new List<string>(line.Split(",")));
                }

            }
            List<Tuple<int, int>> intersection;
            List<Tuple<int, int>> w1 = generateCoordinates(wires[0]);
            List<Tuple<int, int>> w2 = generateCoordinates(wires[1]);
            intersection = w1.Intersect(w2).ToList();

            Dictionary<Tuple<int, int>, int> distances = new Dictionary<Tuple<int, int>, int>();
            int? shortest = null;
            foreach(Tuple<int, int> el in intersection) {
                int distance = Math.Abs(el.Item1) + Math.Abs(el.Item2);
                if(shortest == null || distance < shortest) {
                    shortest = distance;
                }
                distances[el] = Math.Abs(el.Item1) + Math.Abs(el.Item2);
            }
            Write("Shortest distance is {0}", shortest);

        }
        static List<Tuple<int, int>> generateCoordinates(List<string> wire) {
            List<Tuple<int, int>> coords = new List<Tuple<int, int>>();
            // Start at 0,0
            int x = 0;
            int y = 0;
            foreach(string dir in wire) {
                int length =  ToInt32(dir.Substring(1));
                switch(dir[0]) {
                    case 'U':
                        for(int i = 1; i < length; i++) {
                            coords.Add(new Tuple<int,int>(x,y+i));
                        }
                        y = y + length;
                        break;
                    case 'D':
                        for(int i = 1; i < length; i++) {
                            coords.Add(new Tuple<int,int>(x,y-i));
                        }
                        y = y - length;
                        break;
                    case 'L':
                        for(int i = 1; i < length; i++) {
                            coords.Add(new Tuple<int,int>(x-i,y));
                        }
                        x = x - length;
                        break;
                    case 'R':
                        for(int i = 1; i < length; i++) {
                            coords.Add(new Tuple<int,int>(x+i,y));
                        }
                        x = x + length;
                        break;
                    default:
                        WriteLine("Something went terribly wrong");
                        Environment.Exit(1);
                        break;
                }
            }
            return coords;
        }
    }

    class Wire {
        public Wire(List<string> data) {

        }
    }
}

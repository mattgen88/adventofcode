using static System.Console;
using System.IO;
using static System.Convert;
using System.Collections.Generic;
using System;
using static System.Math;

namespace part1
{
    class Program
    {
        static void Main(string[] args)
        {
            StreamReader file = new StreamReader(@"./input.txt");
            string line;
            List<Decimal> masses = new List<Decimal>();

            while ((line = file.ReadLine()) != null)
            {
                masses.Add(ToDecimal(line));
            }

            file.Close();
            List<Decimal> fuel_costs = new List<Decimal>();
            foreach (Decimal mass in masses)
            {
                Decimal fuel = Floor(mass / 3) - 2;
                fuel_costs.Add(fuel);
            }

            Decimal fuel_cost = 0;
            foreach (Decimal cost in fuel_costs)
            {
                fuel_cost = fuel_cost + cost;
                WriteLine(fuel_cost.ToString());
            }
            WriteLine("Total Fuel Cost {0}", fuel_cost);
        }
    }
}

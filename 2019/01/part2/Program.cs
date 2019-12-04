using System;
using System.Collections.Generic;
using System.IO;
using static System.Convert;
using static System.Console;
using static System.Math;

namespace part2
{
    class Program
    {
        static void Main(string[] args)
        {
            StreamReader file = new StreamReader(@"../input.txt");
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
                Decimal fuel = calculateFuel(mass);
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

        public static Decimal calculateFuel(Decimal mass) {
            Decimal cost = 0;
            cost = Floor((mass / 3) - 2);
            if (cost <= 0) {
                return 0;
            }
            return cost + calculateFuel(cost);
        }
    }
}

using static System.Console;
using System.IO;
using static System.Convert;
using System.Collections.Generic;
using System;

namespace part2
{
    class Program
    {
        private const int HALT = 99;
        private const int ADD = 1;
        private const int MULT = 2;
        private List<int> instructions;
        private int ptr = 0;

        static void Main(string[] args)
        {
            StreamReader file = new StreamReader(@"../input.txt");
            List<int> initialInstructions = new List<int>();
            foreach (string instruction in file.ReadLine().Split(",")) {
                initialInstructions.Add(ToInt32(instruction));
            }
            file.Close();
            Program p;
            for (int i = 0; i < initialInstructions.Count; i++) {
                for (int j = 0; j < initialInstructions.Count; j++) {
                    List<int> instructions = new List<int>(initialInstructions);
                    instructions[1] = i;
                    instructions[2] = j;
                    p = new Program(instructions);
                    if (p.instructions[0] == 19690720) {
                        WriteLine("Inputs {0} {1}", i, j);
                        Environment.Exit(0);
                    }
                }
            }
        }

        public Program(List<int> instructions) {
            this.instructions = instructions;
            for(;;) {
                // read instruction at ptr
                int instruction = instructions[this.ptr];
                switch(instruction) {
                    case ADD:
                        this.Add();
                        break;
                    case MULT:
                        this.Mult();
                        break;
                    case HALT:
                        return;
                    default:
                        WriteLine("Unknown instruction {0}", this.instructions[ptr]);
                        Environment.Exit(1);
                        break;
                }
                ptr = ptr + 4;
            }
        }

        public void Add() {
            this.instructions[this.instructions[ptr+3]] = this.instructions[this.instructions[ptr+1]] + this.instructions[this.instructions[ptr+2]];
        }

        public void Mult() {
            this.instructions[this.instructions[ptr+3]] = this.instructions[this.instructions[ptr+1]] * this.instructions[this.instructions[ptr+2]];

        }
    }
}

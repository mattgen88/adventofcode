using static System.Console;
using System.IO;
using static System.Convert;
using System.Collections.Generic;
using System;

namespace part1
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
            List<int> instructions = new List<int>();
            foreach (string instruction in file.ReadLine().Split(",")) {
                instructions.Add(ToInt32(instruction));
            }
            file.Close();
            instructions[1] = 12;
            instructions[2] = 2;
            Program p = new Program(instructions);
            WriteLine("instruction 0 is {0}",p.instructions[0]);
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

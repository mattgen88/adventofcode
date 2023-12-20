<?php

class computer
{
    public int $register_a = 1;
    public int $register_b = 0;
    public array $instructions;
    public int $ptr = 0;

    public function __construct($file)
    {
        $this->instructions=file($file);
        while(true) {
            $this->exec();
        }
    }

    public function exec()
    {
        if (!isset($this->instructions[$this->ptr])) {
            var_dump($this->register_a);
            var_dump($this->register_b);
            exit();
        }
        $instruction = $this->instructions[$this->ptr];
        echo "Executing $instruction\n";
        switch(true) {
            case (str_contains($instruction, 'hlf a')):
                $this->hlf('a');
                break;
            case (str_contains($instruction, 'hlf b')):
                $this->hlf('b');
                break;
            case (str_contains($instruction, 'tpl a')):
                $this->tpl('a');
                break;
            case (str_contains($instruction, 'tpl b')):
                $this->tpl('b');
                break;
            case (str_contains($instruction, 'inc a')):
                $this->inc('a');
                break;
            case (str_contains($instruction, 'inc b')):
                $this->inc('b');
                break;
            case (preg_match('/jmp (\+|-)(\d+)/', $instruction, $matches)):
                if($matches[1] == '+') {
                    $this->jmp((int)$matches[2]);
                } else {
                    $this->jmp(-1*(int)$matches[2]);
                }
                break;
            case (preg_match('/jie (a|b), (\+|-)(\d+)/', $instruction, $matches)):
                if($matches[2] == '+') {
                    $this->jie($matches[1], (int)$matches[3]);
                } else {
                    $this->jie($matches[1],-1*(int)$matches[3]);
                }
                break;
            case (preg_match('/jio (a|b), (\+|-)(\d+)/', $instruction, $matches)):
                if($matches[2] == '+') {
                    $this->jio($matches[1], (int)$matches[3]);
                } else {
                    $this->jio($matches[1], -1*(int)$matches[3]);
                }
                break;
            default:
                exit('EOF');
        }
    }
    private function hlf($register)
    {
        if ($register == 'a') {
            $this->register_a = round($this->register_a / 2);
            $this->ptr++;
            return;
        }
        $this->register_b = round($this->register_b / 2);
        $this->ptr++;
    }

    private function tpl($register)
    {
        $this->ptr++;
        if ($register == 'a') {
            $this->register_a *= 3;
            return;
        }
        $this->register_b *= 3;
    }

    private function inc($register)
    {
        $this->ptr++;
        if ($register == 'a') {
            $this->register_a++;
            return;
        }
        $this->register_b++;
    }

    private function jmp($offset)
    {
        $this->ptr+=$offset;
    }

    private function jie($register, $offset)
    {
        $value = $this->register_a;
        if ($register == 'b') {
            $value = $this->register_b;
        }
        if ($value % 2 === 0)
            $this->jmp($offset); //jmp will change ptr
        else
            $this->ptr++;
    }

    private function jio($register, $offset)
    {
        $value = $this->register_a;
        if ($register == 'b') {
            $value = $this->register_b;
        }
        if ($value === 1)
            $this->jmp($offset); //jmp will change ptr
        else
            $this->ptr++;
    }

}

$computer = new computer('input.txt');
var_dump($computer);



<?php

//BOSS
//Hit Points: 58
//Damage: 9

//ME
//Hit Points: 50
//Mana: 500

//SPELLS
//Magic Missile: 53
//Damage: 4
//
//Drain: 73
//Damage: 2
//Heal: 2
//
//Shield: 113
//Lasts: 6
//Armor: 7
//
//Poison: 173
//Lasts: 6
//Damage: 3
//
//Recharge: 229
//Lasts: 5
//Mana: 101


class battle {
	public ?int $boss_hp = null;
	public ?int $boss_damage = null;

	public ?int $my_hp = null;
	public ?int $my_mana = null;
	public int $my_mana_spent = 0;
	public int $my_armor = 0;

	public int $shield_duration = 0;
	public int $poison_duration = 0;
	public int $recharge_duration = 0;

	public int $turns = 0;

	public array $history = [];

	function __construct($boss_hp, $boss_damage, $my_hp, $my_mana)
	{
	    $this->boss_hp = $boss_hp;
	    $this->boss_damage = $boss_damage;
	    $this->my_hp = $my_hp;
	    $this->my_mana = $my_mana;
	}

    /**
     * @throws DeadMeException
     * @throws DeadBossException

     */
    public function attack_player()
	{
	    $this->history[] = "-- Boss turn --";
        $this->history[] = "- Player has $this->my_hp hit points, $this->my_armor armor, $this->my_mana mana";
        $this->history[] = "- Boss has $this->boss_hp hit points";
	    $this->turns++;
	    $this->apply_effects();
	    $damage = max(1, $this->boss_damage-$this->my_armor);
	    $this->history[] =  "Boss inflicting $damage damage on you, " . ($this->my_hp - $damage) . " hp remains of your health";
		$this->my_hp -= $damage;
		if ($this->my_hp <= 0)
			throw new DeadMeException();
	}

    /**
     * @throws DeadBossException
     * @throws NoManaException
     * @throws SpellAlreadyActiveException
     * @throws DeadMeException
     */
    public function attack_boss($spell)
	{
        $this->history[] = "-- Player turn --";
        $this->history[] = "- Player has $this->my_hp hit points, $this->my_armor armor, $this->my_mana mana";
        $this->history[] = "- Boss has $this->boss_hp hit points";
	    $this->turns++;
        $this->my_hp--;
        if ($this->my_hp <= 0)
        {
            throw new DeadMeException();
        }
	    $this->apply_effects();
	    $this->history[] =  "Casting $spell";
        switch ($spell) {
            case "magic_missile":
                $this->cast_magic_missile();
                break;
            case "poison":
                $this->cast_poison();
                break;
            case "recharge":
                $this->cast_recharge();
                break;
            case "drain":
                $this->cast_drain();
                break;
            case "shield":
                $this->cast_shield();
                break;
            default:
                exit("nope");
        }
	}

    /**
     * @throws DeadBossException
     */
    private function apply_effects()
	{
	    if ($this->shield_duration > 0)
	    {
	        $this->shield_duration--;
            $this->history[] = "Shield's timer is now $this->shield_duration";
	        if ($this->shield_duration <=0)
	        {
	            $this->history[] =  "Shield wore off";
	            $this->my_armor = 0;
            }
	    }

	    if ($this->poison_duration > 0)
	    {
	        $this->poison_duration--;
            $this->boss_hp -= 3;
            $this->history[] =  "Inflicting 3 poison damage, timer is now $this->poison_duration. $this->boss_hp remains of boss's health";
            if ($this->poison_duration <= 0)
                $this->history[] =  "Poison wore off";
	        if ($this->boss_hp <= 0)
	            throw new DeadBossException($this->my_mana_spent);
	    }

	    if ($this->recharge_duration > 0)
	    {
	        $this->recharge_duration--;
	        $this->my_mana += 101;
            $this->history[] = "Recharge provides 101 mana, now have $this->my_mana mana; its timer is now $this->recharge_duration.";
            if ($this->recharge_duration <= 0)
                $this->history[] =  "Recharge wore off";
	    }
	}

    /**
     * @param $amount
     * @return void
     * @throws NoManaException
     */
    private function spend_mana($amount)
	{
       if ($this->my_mana - $amount <= 0)
                throw new NoManaException();
            $this->my_mana -= $amount;
            $this->my_mana_spent += $amount;
            $this->history[] =  "Spending $amount mana, $this->my_mana mana remains. Total spent $this->my_mana_spent.";
	}

    /**
     * @throws NoManaException
     * @throws DeadBossException
     */
    private function cast_magic_missile()
	{
	    $this->spend_mana(53);

	    $this->boss_hp -= 4;
        $this->history[] =  "Inflicting 4 damage, $this->boss_hp boss hp remains";
	    if ($this->boss_hp <= 0)
	        throw new DeadBossException($this->my_mana_spent);
	}

    /**
     * @throws NoManaException
     * @throws DeadBossException
     */
    private function cast_drain()
	{
	    $this->spend_mana(73);
        $this->boss_hp -= 2;
        $this->my_hp += 2;
        $this->history[] =  "Inflicting 2 damage, $this->boss_hp boss hp remains. Healing hit points for $this->my_hp hit points.";
	    if ($this->boss_hp <= 0)
	        throw new DeadBossException($this->my_mana_spent);
	}

    /**
     * @throws NoManaException
     * @throws SpellAlreadyActiveException
     */
    private function cast_shield()
    {
	    $this->spend_mana(113);

        if ($this->shield_duration > 0)
            throw new SpellAlreadyActiveException();
        $this->shield_duration = 6;
        $this->my_armor = 7;
    }

    /**
     * @throws NoManaException
     * @throws SpellAlreadyActiveException
     */
    private function cast_poison()
    {
	    $this->spend_mana(173);

        if ($this->poison_duration > 0)
            throw new SpellAlreadyActiveException();
        $this->poison_duration = 6;
    }

    /**
     * @throws NoManaException
     * @throws SpellAlreadyActiveException
     */
    private function cast_recharge()
    {
	    $this->spend_mana(229);

	    if ($this->recharge_duration > 0)
	        throw new SpellAlreadyActiveException();
        $this->recharge_duration = 5;
    }
}


class DeadBossException extends Exception {
    public int $mana_spent = 0;

    function __construct($mana_spent)
    {
        $this->mana_spent=$mana_spent;
        parent::__construct();
    }
}

class DeadMeException extends Exception {

}

class NoManaException extends Exception {

}

class SpellAlreadyActiveException extends Exception {

}
$input = file_get_contents("input.txt");
$data = expode("\n", $input);
$hp = explode(": ", $data[0])[1];
$damage = explode(": ", $data[1])[1];
$battles = [new battle($hp, $damage, 50, 500)];

// for each of the battle instances
// try each of the spells
// if an exception occurs, that choice is a dead end
$lowest_cost = -1;
while(true)
{
    $new_battles = [];

    // iterate over the surviving battles
    foreach ($battles as $battle)
    {
        for ($i =0; $i <= 4; $i++)
        {
            $new_battle = clone($battle);
            try
            {
                switch($i)
                {
                    case 0:
                        $new_battle->attack_boss("magic_missile");
                        break;
                    case 1:
                        $new_battle->attack_boss("drain");
                        break;
                    case 2:
                        $new_battle->attack_boss("shield");
                        break;
                    case 3:
                        $new_battle->attack_boss("poison");
                        break;
                    case 4:
                        $new_battle->attack_boss("recharge");
                        break;
                    default:
                        exit("nope");
                }
                $new_battle->attack_player();
            }
            catch (NoManaException|SpellAlreadyActiveException|DeadMeException $e)
            {
                continue;
            } catch (DeadBossException $e)
            {
                echo "Got em! mana cost of $e->mana_spent\n";
                foreach($new_battle->history as $entry)
                {
                    echo $entry."\n";
                }
                echo "\n";
                if ($lowest_cost == -1 || $e->mana_spent < $lowest_cost)
                {
                    $lowest_cost = $e->mana_spent;
                    echo "New low cost of $lowest_cost!\n";
                }
            }

            // Skip this battle if it already cost more than current lowest
            if ($lowest_cost != -1 && $new_battle->my_mana_spent >= $lowest_cost)
            {
                continue;
            }
            // Add to surviving battles
            $new_battles[] = $new_battle;
        }
    }
    $battles = $new_battles;
    if (count($battles) == 0)
    {
        break;
    }
}
var_dump($lowest_cost);


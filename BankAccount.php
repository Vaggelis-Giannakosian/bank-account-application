<?php


abstract class BankAccount
{
    protected $balance = 0;
    public $APR;
    public $sortCode;
    public $firstName;
    public $lastName;
    public $audit = array();
    protected $locked = false;

    public function withdraw($amount){

        $transDate = new DateTime();

        if($this->locked === false){
            $this->balance -= $amount;
            array_push($this->audit,array('Withdraw accepted',$amount,$this->balance,$transDate->format('c')));
        }else
            {
                array_push($this->audit,array('Withdraw denied',$amount,$this->balance,$transDate->format('c')));
            }


    }

    public function deposit($amount){

        $transDate = new DateTime();

        if($this->locked === false){
            $this->balance += $amount;
            array_push($this->audit,array('Deposit accepted',$amount,$this->balance,$transDate->format('c')));
        }else
        {
            array_push($this->audit,array('Deposit denied',$amount,$this->balance,$transDate->format('c')));
        }


    }

    public function lock()
    {
        $this->locked = true;
        $lockedDate = new DateTime();
        array_push($this->audit,array('Account locked',$lockedDate->format('c')));
    }

    public function unlock()
    {
        $this->locked = false;
        $unlockedDate = new DateTime();
        array_push($this->audit,array('Account unlocked',$unlockedDate->format('c')));
    }

}








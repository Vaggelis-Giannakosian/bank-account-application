<?php
require('BankAccount.php');

class ISA extends BankAccount
{
    public $timePeriod = 28;
    public $additionalServices;

    //Methods

    public function withdraw($amount)
    {
        $transDate = new DateTime();
        $lastTransaction = null;
        $lenght = count($this->audit);
        for ($i = $lenght; $i > 0; $i--) {
            $element = $this->audit[$i - 1];
            if ($element[0] === "Withdraw accepted") {
                $days = new DateTime($element[3]);
                $lastTransaction = $days->diff($transDate)->format("%a");
                break;
            }

        }

        if ($lastTransaction === null && $this->locked === false || $this->locked === false && $lastTransaction > $this->timePeriod) {
            $this->balance -= $amount;
            array_push($this->audit, array('Withdraw accepted', $amount, $this->balance, $transDate->format('c')));
        } else {
            if ($this->locked === false) {
                $this->balance -= $amount;
                array_push($this->audit, array('Withdraw accepted with penalty', $amount, $this->balance, $transDate->format('c')));
                $this->penalty();
            } else {
                array_push($this->audit, array('Withdraw denied', $amount, $this->balance, $transDate->format('c')));
            }

        }


    }

    private function penalty()
    {
        $transDate = new DateTime();
        $this->balance -= 10;
        array_push($this->audit, array('Withdraw penalty', 10, $this->balance, $transDate->format('c')));

    }


}



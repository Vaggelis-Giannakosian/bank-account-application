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

class Savings extends BankAccount implements AccountPlus, Savers
{
    use SavingsPlus;
    public $pocketBook = array();
    public $depositBook = array();

    public function orderNewBook()
    {
        $orderTime = new DateTime();
        array_push($this->pocketBook, "Ordered new pocket book on: " . $orderTime->format('c'));
    }

    public function orderNewDepositBook()
    {
        $orderTime = new DateTime();

        array_push($this->pocketBook, "Ordered new deposit book on: " . $orderTime->format('c'));
    }


}

class Debit extends BankAccount implements AccountPlus
{
    use SavingsPlus;
    private $cardNumber;
    private $securityCode;
    private $pinNumber;

    public function validate()
    {

        $valDate = new DateTime();
        $this->cardNumber = rand(1000, 9999) . "-" . rand(1000, 9999) . "-" . rand(1000, 9999) . "-" . rand(1000, 9999);
        $this->securityCode = rand(100, 999);
        array_push($this->audit, array("Validated card", $valDate->format('c'), $this->cardNumber, $this->securityCode, $this->pinNumber));
    }

    public function changePin($newPin)
    {
        $pinChange = new DateTime();
        $this->pinNumber = $newPin;
        array_push($this->audit, array("Pin changed", $pinChange->format('c'), $this->pinNumber));
    }

}

trait SavingsPlus
{
    private $monthlyFee = 20;
    public $package = "Holiday insurance";

    public function addedBonus()
    {
        echo "Hello" . $this->firstName . " " . $this->lastName . ", for &pound;" . $this->monthlyFee . " a month you get " . $this->package;
    }

}

interface AccountPlus
{
    public function addedBonus();
}

interface Savers
{
    public function orderNewBook();

    public function orderNewDepositBook();
}


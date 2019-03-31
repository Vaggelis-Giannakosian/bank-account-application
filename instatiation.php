<?php

require('SubClasses.php');

$account1 = new ISA(35, "holiday package", 5.0, "20-20-20", "Lostre", "Turton");

$account1->deposit(1000);
$account1->withdraw(1000);
$account1->withdraw(100);
$account1->withdraw(100);
$account1->lock();
$account1->deposit(1000);
$account1->withdraw(100);
//echo json_encode($account1,JSON_PRETTY_PRINT);
$account2 = new Savings(50, 'Cartoon insurance', 12.0, "20-58-20", "Justin", "Dike");

$account2->deposit(1000);
$account2->lock();
$account2->withdraw(200);
$account2->unlock();
$account2->withdraw(300);

//echo json_encode($account2,JSON_PRETTY_PRINT);
$account3 = new Debit(30, 'great package', '1234', 0, "20-50-20", "Jason", "Bourne", "Spy insurance");
$account3->deposit(1000);
$account3->lock();
$account3->withdraw(200);
$account3->unlock();
$account3->withdraw(300);
//$account3->addedBonus();
//echo json_encode($account3,JSON_PRETTY_PRINT);
//array
$accountlist = array($account1, $account2, $account3);

foreach ($accountlist as $account) {
    $print = $account->firstName;
    if ($account instanceof AccountPlus) {
        $print .= " addedBonu() ";
    }

    if ($account instanceof Savers) {
        $print .= ' orderNewBook() and  orderNewDepositBook() ';
    }

    echo $print . '<br>';
}





<?php
/**
 * Created by PhpStorm.
 * User: panayiotisgeorgiou
 * Date: 29/05/15
 * Time: 12:52
 */

$year = '2015';
$month = '06';
$day = '7';

$current_date = new DateTime(date('Y-m-d'), new DateTimeZone('Europe/London'));
$end_date = new DateTime("$year-$month-$day", new DateTimeZone('Europe/London'));
$interval = $current_date->diff($end_date);
echo $interval->format('%a day(s)').'<br>';
echo 'Current date:'.date('d-m-Y').'<br>';
echo 'Offer date:'.$end_date->format('d-m-Y').'<br>';

$results_numeric = (int) $interval;
$val = intval($interval->format('%a'));

if($val == 0){
    echo 'Offer expires today';
}
if($val == 1){
    echo 'Offer expires tomorrow';
}
if(($val >=2) && ($val <=6)){
    echo 'Offer expires in '.$interval->format('%a day(s)').'<br>';
}
if($val >=7){
    echo 'Offer expires in '.$end_date->format('d-m-Y').'<br>';
}
?>

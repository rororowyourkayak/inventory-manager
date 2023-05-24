<?php
namespace App;

class CustomFunctions {
    public static function isValidUPC(string $check){

        if(is_numeric($check) && strlen($check) == 12){
     
           $split_digits = str_split($check);
           $check_digit = number_format($split_digits[11]);
     
           $oddSum = $evenSum = 0; 
           for($n = 0; $n < 11; $n++){
              
               $temp = number_format($split_digits[$n]);
               
              if($n % 2 == 0){
                 $oddSum+=$temp;
              }
              else{
                 $evenSum+=$temp; 
              }
           }
     
           $oddSum *= 3;
           $totalSum = $oddSum + $evenSum; 
     
           if( ($totalSum + $check_digit) % 10 == 0 ) return true;
     
        }
     
        return false; 
     }
}

?>
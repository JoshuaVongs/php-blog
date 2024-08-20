<?php
    $a = "";
    for($i = 1; $i<=7; $i++){
        $a.="#";
        echo "$a<br>";
    }

    for ($i=1; $i < 101;$i++) {
      if ($i % 3 == 0) {
        echo"Fizz <br>"; 
      }elseif ($i % 5 == 0) {
        echo"Buzz <br>";
      }elseif ($i % 3 == 0 & $i % 5 == 0){
        echo"FizzBuzz<br>";
      }else {
        echo "$i<br>";
      }
    }
    
?>
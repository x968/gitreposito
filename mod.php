<?php
define("SUMM","sum.txt");
include 'model/db.php';

//Запись в файл общей суммы + введенное значение
function writeTofile($fsum){
    $summa=fopen(SUMM, "r") or die("error");
        $allsum=fread($summa, filesize(SUMM));
    fclose($summa);
    $allsum += $fsum;
    file_put_contents(SUMM,$allsum);
          
}

//Запись в БД
function writeTodb($fsum, $freason, $link){
  
    $time = time();
    
    //NOW()
    $sql = "INSERT INTO t_budget (id, sum, msg, date) VALUES ('NULL', '$fsum', '$freason', '$time')";
  
    $link->query($sql);

    
   
}
//Запись в t_months бюджета

function writeToDB2($link){
    $summa=fopen(SUMM, "r") or die("error");
            $allsum=fread($summa, filesize(SUMM));
            fclose($summa);
    $time = time();
    $sql = "INSERT INTO t_months (id, sum, month) VALUES (NULL, '$allsum', '$time')";
    $link->query($sql); 
    
    
    
}
//Сброс общей суммы
function restBudget(){
        $allsum = 000000;    
        file_put_contents(SUMM,$allsum);
}
//Вывод итоговой суммы


function itogSummaprint(){
            $summa=fopen(SUMM, "r") or die("error");
            return $allsum=fread($summa, filesize(SUMM));
            fclose($summa);
             
    
}
//Запись в t_months общей суммы и id (!должна быть заполнена первая строка)
function saveMonth($n){
       $months = array(

            '01'=>'Январь',
            '02'=>'Февраль',
            '03'=>'Март',
            '04'=>'Апрель',
            '05'=>'Май',
            '06'=>'Июнь',
            '07'=>'Июль',
            '08'=>'Август',
            '09'=>'Сентябрь',
            '10'=>'Октябрь',
            '11'=>'Ноябрь',
            '12'=>'Декабрь'        
            
);

   
  
    
    return $months[date("m", $n)]  .'&nbsp&nbsp' .date("Y", $n);
 

 
    
    //Запись allsum, $prev_id, month в t_months
    
    
    
}


//Вывод в денежном формате
   
function vyvodDenformat($str){
  $co=strlen($str)-1;
  
  $len=strlen($str)-1;
  if($co >= 3){
      $co-=3;
      
     
     if(($co >= 0) and ($co < 3)){
         
         for($i=0; $i <= $co; $i++){
             $vyvod .= $str{$i};
             
             
         }
            $vyvod .=' ';
            $co++;
            
          for($i = $co; $i <= $len; $i++){
           $vyvod .= $str{$i};
          }
            
          
          
         
     }
        else
          
            return $str;
      
  }
        else
            return $str;
        
        
    return $vyvod;


 }
    
    
    
    
   

//Вывод списка покупок
function totalList($link){
    

    $sql = "SELECT sum, msg, date FROM t_budget ORDER BY id DESC";
    $result = $link->query($sql);
    
    while($row = mysqli_fetch_array($result)){
        $goods[] = $row;
    }
    
            
        
            
    return $goods;    
    
}
function statisticTomonth($data, $link){
//Получить предыдущую data
    

    $sql = "SELECT id FROM t_months WHERE month = $data";
    $result = $link->query($sql);
    
    
    
    while($row = mysqli_fetch_array($result)){
        $id = $row['id'];
    }
        
    
    //проверка на id 
    $sql = "SELECT COUNT(*) FROM t_months";
        $result = $link->query($sql);
     $row = mysqli_fetch_row($result);
        $id1 = $row['0'];
     //проверка на первую запись в таблице
     $sql = "SELECT MIN(id) FROM t_months";
        $result = $link->query($sql);
     $row = mysqli_fetch_row($result);
        $minid = $row['0'];
    
    
    
    
    if($id1 >1){
        
            if($minid == $id){
             $sql = "SELECT * FROM t_budget where date <= $data ORDER BY id DESC";
        $result = $link->query($sql);
        while($row = mysqli_fetch_array($result)){
            $static[] = $row;
        }  
        }
            else
        $id --;
        //Превед id
        
        $sql = "SELECT month FROM t_months WHERE id = $id";
        $result = $link->query($sql);
     while($row = mysqli_fetch_array($result)){
        $pmonth = $row['month'];
    }
    //Преведущая дата
    

        $sql = "SELECT * FROM t_budget where date > $pmonth and date <= $data ORDER BY id DESC";
        $result = $link->query($sql);
        while($row = mysqli_fetch_array($result)){
            $static[] = $row;
            
        }
    
    
    
    }
    
        else {
            
        $sql = "SELECT * FROM t_budget where date <= $data ORDER BY id DESC";
        $result = $link->query($sql);
        while($row = mysqli_fetch_array($result)){
            $static[] = $row;
            
        }    
        
              
            
            
        }
        
            return $static;
    
}

//Вывод месеца и расхода

function totalMonth($link){
    

    $sql = "SELECT sum, month FROM t_months ORDER BY id DESC LIMIT 10";
    $result = $link->query($sql);
    if(mysqli_fetch_array($result)){
    while($row = mysqli_fetch_array($result)){
        $months[] = $row;
    }
    
    return $months;    
    }
    else
        die("No information");
}











?>
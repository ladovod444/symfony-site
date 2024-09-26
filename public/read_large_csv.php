<?php

function get_memory_use()
{
    echo memory_get_peak_usage(true) * 0.0000010 . ' ' . 'MB';
}

$filename = './uploads/products.csv';
$row = 0;
$import = fopen($filename, 'r');



//Bad Example
/*
echo 'Bad Example<br>'; // 132.124672 MB
$rows= [];
$handle = fopen($filename, "r");
if ($handle) {
    while (!feof($handle)) {
        $rows[] = fgets($handle, 1000);
    }
    fclose($handle);
}

echo count ($rows) ."<br>"; // 132.124672 MB

 $count = 0;
foreach ($rows as $csv) {
 $count++;
 //skip header
 if ($count == 1) {
   continue;
 }
 $row = explode(',', $csv);
    echo '<pre>';
    print_r($row);
    echo '</pre>';
 
 // Do something
} 
// 132 124 672
//   209 7152
*/

/* echo get_memory_use();
echo '<br>';
exit; */

echo 'Much Better Example<br>';
if ($import) {
    //echo 'file_exists<br />';
    //$rows = [];
    while ($data = fgetcsv($import)) {
        $row++;
        //skip header row
        if ($row == 1) {
            continue;
        }

        //$rows[] = $data;

        if ($row > 1000) {
            break;
        }

       /*  echo '<pre>';
        print_r($data);
        echo '</pre>'; */

        // Process csv row  !!!
    }
    echo $row-1 . "<br>"; // 2.097152 MB
    //echo count($rows) . "<br>"; // 446.697472 MB // Если 

    get_memory_use(); // = 14.680064 MB  1000 строк 
                      //  = 16.777216 MB
    // 2097152  - if 30 rows 
    // 2097152  - also if 3 rows
} else {
    echo 'error reading file';
}
die();


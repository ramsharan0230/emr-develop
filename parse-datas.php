<?php


$db_host = "localhost";
$db_name = "freshcogent";
$db_user = "root";
$db_pass = "";

try {
    $db_con = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8", $db_user, $db_pass);
    $db_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db_con->exec("set names utf8");
} catch (PDOException $e) {
    echo $e->getMessage();
}

$machinename = $_POST["machinename"];
$dump = $_POST["dump"];
$header = $_POST["header"];
$rd = date('Y-m-d H:i:s');


try {
    echo "here";
    $sql123 = $db_con->prepare("insert into tbl_dump (machinename,dump,header,rd) values ('$machinename','$dump','$header','$rd')");
    $sql123->execute();
    $getmachine = $db_con->prepare("select code,test from machine_map");
    $mapping = $db_con->query($getmachine);
    $rowdata = explode("----", $dump);
    $nameSampleId = explode(' ', $rowdata[0]);
    $sampleid = $nameSampleId[0];
    foreach ($rowdata as $rowKey => $rowValue) {
        if ($rowKey != 0) {
            if ($rowValue) {
                // $sampleid = trim($rowValue[0]);
                $testnameline = explode('**', $rowValue);
                $testname1 = trim($testnameline[0]);
                $testvalue = trim($testnameline[1]);



                if (array_key_exists($testname1, $mapping)) {
                    $testname = $mapping[$testname1];
                } else {
                    $testname = $testname1;
                }


                // $search_array = array_map('strtolower', $tbltests);

                $pattest = $db_con->prepare("select fldtestid from tblpatlabtest where fldstatus='Sampled'  and fldsampleid='" . $sampleid . "' and fldtestid='$testname'");
                $checkInSample = $db_con->query($pattest);
                echo "Code from dump: ".$testnameline[0];
                print_r($checkInSample);
                echo  '<br/>';
                echo $sampleid . '<br/>';
                echo $testname . '<br/>';
               
            }
        }
    }
} catch (\Exception $e) {
    echo $e->getMessage();
}
die('Done');

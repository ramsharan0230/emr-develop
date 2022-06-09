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


$machinename = 'cobas';//$_POST["machinename"];
$dump = '123----735**25';//$_POST["dump"];
$header = 'head';//$_POST["header"];
$rd = date('Y-m-d H:i:s');


try {

   // $tbltests = DB::table('tbltest')->pluck('fldtestid')->toArray();
    $tbltests_query  = "select fldtestid  from tbltest";
    $tbltests = mysqli_query($db_con, $tbltests_query);
    print_r($tbltests); die();

    if ($machinename == 'hematology') {
      // echo $machinename.'<br>';
        if (!empty($dump)) {
           // echo $dump.'<br>';
            $rowdata = explode("----", $dump);


            $nameSampleId = explode(' ', $rowdata[0]);
            $sampleid = $nameSampleId[0];
            //echo $sampleid.'<br>';

            foreach ($rowdata as $rowKey => $rowValue) {
                if ($rowKey != 0) {
                    if ($rowValue) {

                        $testnameline = explode('**', $rowValue);
                        $testname1 = trim($testnameline[0]);
                        $testvalue = trim($testnameline[1]);
                        $reportedtime = "CURRENT_TIMESTAMP";


                        if (isset($mapping[$testname1])) {
                            $testname = $mapping[$testname1];
                        } else {
                            $testname = $testname1;
                        }


                        $search_array = array_map('strtolower', $tbltests);

                        // echo $testname.'<br>';
                        // print_r($tbltests);

                        if (in_array($testname, $tbltests)) {
                            $check_in_sampleid = "";
                            $check_in_sampleid = "select * from tblpatlabtest where fldstatus='Sampled'  and fldsampleid='" . $sampleid . "' and fldtestid='" . $testname . "'";

                            $collected_sampled_data = mysqli_query($db_con, $check_in_sampleid);
                            if (mysqli_num_rows($collected_sampled_data) > 0) {
                                while ($row = mysqli_fetch_assoc($collected_sampled_data)) {

                                    $subtest_query  = "select fldsubtest  from tblpatlabsubtest where fldtestid ='" . $row['fldid'] . "' ";
                                    $check_subtest = mysqli_query($db_con, $subtest_query);

                                    if ($check_subtest) {
                                        $update_subtest = "UPDATE tblpatlabsubtest SET fldreport=" . trim($testvalue) . ",fldstatus='Reported',fldtime_report=" . $reportedtime . " WHERE fldtestid='" . $row['fldid'] . "' and fldsubtest='" . $row['fldsubtest'] . "'";

                                        if ($db_con->query($update_subtest) === TRUE) {
                                            echo "Record updated patlabsubtest successfully";
                                        } else {
                                            echo "Error updating record: " . $db_con->error;
                                        }
                                    }

                                    $update_labtest = "UPDATE tblpatlabtest SET fldreportquanti=" . trim($testvalue) . ",fldreportquali=" . trim($testvalue) . ",fldstatus='Reported',fldtime_report=" . $reportedtime . ",xyz=0,fldsave_report=1,fldcomp_report='comp04',fldtestunit='SI'" . " WHERE fldsampleid='" . $sampleid . "' and fldtestid='" . $testname . "'";

                                    if ($db_con->query($update_labtest) === TRUE) {
                                        echo "Record updated  patlabtest successfully";
                                    } else {
                                        echo "Error updating record: " . $db_con->error;
                                    }


                                    $insert_dump_query = "INSERT INTO tbl_dump (machinename,dumpdata,header,rd, pat_lab_insert)VALUES ('$machinename','$dump','$header','$rd','1')";

                                    if ($conn->query($insert_dump_query) === TRUE) {
                                        echo "New record created dump successfully";
                                    } else {
                                        echo "Error: " . $sql . "<br>" . $db_con->error;
                                    }
                                }
                            } else {
                                echo "No sample Id" . '<br/>';
                            }
                        }
                    }
                }
            }
        }
    }


    if ($machinename == "cobas") {
        if ($dump) {

            $rowdata = explode("----", $dump);
            if (!empty($rowdata)) {
                $sampleid = $rowdata[0];

                $test = explode("**", $rowdata[1]);

                $testCode  = $test[0];
                $testresult = $test[1];


                $testname1 = trim($testCode);
                $testvalue = trim($testresult);
                $reportedtime = "CURRENT_TIMESTAMP";


                if (isset($mapping[$testname1])) {
                    $testname = $mapping[$testname1];
                } else {
                    $testname = $testname1;
                }


                $search_array = array_map('strtolower', $tbltests);

                if (in_array($testname, $tbltests)) {
                    $check_in_sampleid = "";
                    $check_in_sampleid = "select * from tblpatlabtest where fldstatus='Sampled'  and fldsampleid='" . $sampleid . "' and fldtestid='" . $testname . "'";

                    $collected_sampled_data = mysqli_query($db_con, $check_in_sampleid);
                    if (mysqli_num_rows($collected_sampled_data) > 0) {
                        while ($row = mysqli_fetch_assoc($collected_sampled_data)) {

                            $subtest_query  = "select fldsubtest  from tblpatlabsubtest where fldtestid ='" . $row['fldid'] . "' ";
                            $check_subtest = mysqli_query($db_con, $subtest_query);

                            if ($check_subtest) {
                                $update_subtest = "UPDATE tblpatlabsubtest SET fldreport=" . trim($testvalue) . ",fldstatus='Reported',fldtime_report=" . $reportedtime . " WHERE fldtestid='" . $row['fldid'] . "' and fldsubtest='" . $row['fldsubtest'] . "'";

                                if ($db_con->query($update_subtest) === TRUE) {
                                    echo "Record updated patlabsubtest successfully";
                                } else {
                                    echo "Error updating record: " . $db_con->error;
                                }
                            }

                            $update_labtest = "UPDATE tblpatlabtest SET fldreportquanti=" . trim($testvalue) . ",fldreportquali=" . trim($testvalue) . ",fldstatus='Reported',fldtime_report=" . $reportedtime . ",xyz=0,fldsave_report=1,fldcomp_report='comp04',fldtestunit='SI'" . " WHERE fldsampleid='" . $sampleid . "' and fldtestid='" . $testname . "'";

                            if ($db_con->query($update_labtest) === TRUE) {
                                echo "Record updated  patlabtest successfully";
                            } else {
                                echo "Error updating record: " . $db_con->error;
                            }


                            $insert_dump_query = "INSERT INTO tbl_dump (machinename,dumpdata,header,rd, pat_lab_insert)VALUES ('$machinename','$dump','$header','$rd','1')";

                            if ($conn->query($insert_dump_query) === TRUE) {
                                echo "New record created dump successfully";
                            } else {
                                echo "Error: " . $sql . "<br>" . $db_con->error;
                            }
                        }
                    } else {
                        echo "No sample Id" . '<br/>';
                    }
                }
            }
        }
    }

    if ($machinename == "Biochem_siemens") {

        if ($dump) {

            $rowdata = preg_split("/[\t]/", $dump);


            if ($rowdata) {

                $sampleid = trim($rowdata[0], '"');
                $testvalue = trim($rowdata[7], '"');
                $testname1 = trim($rowdata[3], '"');

                $reported_date = $rowdata[14];
                if ($reported_date == '"---"' || $reported_date == "") {
                    $reportedtime = 'CURRENT_TIMESTAMP';
                } else {
                    $reportedtime = strtotime("$reported_date");
                }


                if (isset($mapping[$testname1])) {
                    $testname = $mapping[$testname1];
                } else {
                    $testname = $testname1;
                }


                $search_array = array_map('strtolower', $tbltests);


                if (in_array(strtolower($testname), $search_array)) {
                    $check_in_sampleid = "";
                    $check_in_sampleid = "select * from tblpatlabtest where fldstatus='Sampled'  and fldsampleid='" . $sampleid . "' and fldtestid='" . $testname . "'";

                    $collected_sampled_data = mysqli_query($db_con, $check_in_sampleid);
                    if (mysqli_num_rows($collected_sampled_data) > 0) {
                        while ($row = mysqli_fetch_assoc($collected_sampled_data)) {

                            $subtest_query  = "select fldsubtest  from tblpatlabsubtest where fldtestid ='" . $row['fldid'] . "' ";
                            $check_subtest = mysqli_query($db_con, $subtest_query);

                            if ($check_subtest) {
                                $update_subtest = "UPDATE tblpatlabsubtest SET fldreport=" . trim($testvalue) . ",fldstatus='Reported',fldtime_report=" . $reportedtime . " WHERE fldtestid='" . $row['fldid'] . "' and fldsubtest='" . $row['fldsubtest'] . "'";

                                if ($db_con->query($update_subtest) === TRUE) {
                                    echo "Record updated patlabsubtest successfully";
                                } else {
                                    echo "Error updating record: " . $db_con->error;
                                }
                            }

                            $update_labtest = "UPDATE tblpatlabtest SET fldreportquanti=" . trim($testvalue) . ",fldreportquali=" . trim($testvalue) . ",fldstatus='Reported',fldtime_report=" . $reportedtime . ",xyz=0,fldsave_report=1,fldcomp_report='comp04',fldtestunit='SI'" . " WHERE fldsampleid='" . $sampleid . "' and fldtestid='" . $testname . "'";

                            if ($db_con->query($update_labtest) === TRUE) {
                                echo "Record updated  patlabtest successfully";
                            } else {
                                echo "Error updating record: " . $db_con->error;
                            }


                            $insert_dump_query = "INSERT INTO tbl_dump (machinename,dumpdata,header,rd, pat_lab_insert)VALUES ('$machinename','$dump','$header','$rd','1')";

                            if ($conn->query($insert_dump_query) === TRUE) {
                                echo "New record created dump successfully";
                            } else {
                                echo "Error: " . $sql . "<br>" . $db_con->error;
                            }
                        }
                    } else {
                        echo "No sample Id" . '<br/>';
                    }
                }
            }
        }
    }

    
} catch (\Exception $e) {
    echo $e->getMessage();
}
die('Done');

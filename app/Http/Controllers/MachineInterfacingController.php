<?php

namespace App\Http\Controllers;

use App\DumpTable;
use App\MachineMap;
use App\PatientInfo;
use App\PatLabTest;
use App\Test;
use Illuminate\Http\Request;
use \DB;
use File;


class MachineInterfacingController extends Controller
{

    public function parse_data_hematology()
    {

        $path = 'C:\Users\Pooja\Desktop\hematology'; //storage_path('hematology'); ///home/cogent/Desktop/lis/

        $files = glob("$path/*.txt");


        foreach ($files as $file) {
            $filesinfolder = file_get_contents($file);

            $header = [];


            $line_arr = explode("\n", $filesinfolder);
            $count_line = count($line_arr);
            //dd($line_arr);


            $name = 'O';
            $test = 'R';
            $end_test = 'L';
            $res = "";
            $sample_counter = 1;
            $sample_id = "";
            $enc_id = "";
            $all_arr = array();
            $return = '';

            foreach ($line_arr as $key => $line) {

                $firstCharacter = substr($line, 0, 1);
                //  echo  $firstCharacter;
                $name_arr = array();
                if ($firstCharacter == $name) {

                    $name_arr = explode("^", $line);
                    // dd($name_arr);

                    $sample_id = trim($name_arr[2]);
                    $sample_id = str_replace("|", "", $sample_id);

                    $res = "";
                    $enc_id = "";
                    $res = $sample_id;
                    $enc_id_arr = explode(" ", $res);
                    $enc_id = $enc_id_arr[0];
                }
                //print_r($res);
                $test_arr = array();
                if ($firstCharacter == $test) {
                    $test_arr = explode("|", $line); // res_line.split();
                    //print_r($test_arr);
                    $test_name = trim($test_arr[2]);
                    //dd($test_arr);
                    $test_name = str_replace("^1", "", $test_name);
                    $test_name = str_replace("^", "", $test_name);
                    $test_res = trim($test_arr[3]);
                    if (!empty($enc_id)) {
                        $return = $enc_id . "||" . $test_name . "||" . $test_res . '||' . end($test_arr);
                    }

                    //  dd($res);


                }

                if (!empty($return)) {


                    $dumpData['machinename'] = 'hematology';
                    $dumpData['dump'] = $return;
                    $dumpData['rd'] = now();
                    $dumpData['header'] = '';

                    try {


                        DumpTable::create($dumpData);
                    } catch (\Exception $e) {
                        //            dd($e);

                    }
                }
            }

            $file_arr = explode('\\', $file);
            if (!File::exists(dirname(storage_path("read_interfacing/" . end($file_arr))))) {
                File::makeDirectory(dirname(storage_path("read_interfacing/" . end($file_arr))), null, true);
            }
            File::move(storage_path(end($file_arr)), storage_path("read_interfacing/" . end($file_arr)));
        }
    }

    public function parse_data_biochem_siemens()
    {

        $path = storage_path('biochem_siemens'); ///home/ah-01/Desktop/lIS/server/output/

        $files = glob("$path/*.txt");
        foreach ($files as $fileread) {
            $file = fopen($fileread, "r");
            //dd($file);
            $header = [];

            $ara = array();
            $members = array();
            $countDump = 0;
            while (!feof($file)) {
                if ($countDump == 0) {
                    $ara[] = preg_split("/[\t]/", fgets($file));
                } else {
                    $members[] = fgets($file);
                }
                $countDump++;
            }

            fclose($file);

            //  dd($members);


            $dumpData['machinename'] = 'Biochem_siemens';
            $dumpData['header'] = '';
            for ($countData = 0; $countData < count($members); $countData++) {

                try {
                    $dumpData['dump'] = $members[$countData];
                    if ($dumpData['dump'] != '""	""	""	""	""' || $dumpData['dump'] != 0) {
                        DumpTable::create($dumpData);
                    }
                } catch (\Exception $e) {
                    //            dd($e);
                }
            }

            $file_arr = explode('\\', $file);
            if (!File::exists(dirname(storage_path("read_interfacing/" . end($file_arr))))) {
                File::makeDirectory(dirname(storage_path("read_interfacing/" . end($file_arr))), null, true);
            }
            File::move(storage_path(end($file_arr)), storage_path("read_interfacing/" . end($file_arr)));
        }
    }

    public function hematologyInterfacing()
    {
        $mapping = DB::table('machine_map')->pluck('test', 'code');

        $machineName = "hematology";

        $tbltests = DB::table('tbltest')->pluck('fldtestid')->toArray();

        $tblsubtests = DB::table('tblsubtestquali')->pluck('fldsubtest')->toArray();

        $machineDump = DumpTable::where('pat_lab_insert', 0)->where('machinename', $machineName)->get();

        if ($machineDump) {
            foreach ($machineDump as $dump) {
                $dumpid = $dump->id;

                $rowdata = explode("----", $dump->dumpdata);


                $nameSampleId = explode(' ', $rowdata[0]);
                $sampleid = $nameSampleId[0];

                echo 'Sample id ' . $sampleid . '<br>';

                foreach ($rowdata as $rowKey => $rowValue) {

                    if ($rowKey != 0) {

                        if ($rowValue) {

                            $testnameline = explode('**', $rowValue);

                            $testname1 = trim($testnameline[0]); // code of the test
                            $testvalue = trim($testnameline[1]); // value of the test

                            $reportedtime = "CURRENT_TIMESTAMP";

                            $testlist = MachineMap::where('code', $testname1)->get();

                            if ($testlist) {
                                foreach ($testlist as $test) {
                                    $testname = $test->test;
                                    echo 'Testname : ' . $testname;

                                    $check_in_sampleid = "";
                                    $check_in_sampleid = "select * from tblpatlabtest where fldstatus='Sampled'  and fldsampleid='" . $sampleid . "' and fldtestid='" . $testname . "'";

                                    echo $check_in_sampleid . '<br/>';

                                    $collected_sampled_data = DB::select(DB::raw($check_in_sampleid));

                                    if (!empty($collected_sampled_data)) {
                                        foreach ($collected_sampled_data as $sampled_data) {
                                            if (!empty($sampled_data)) {


                                                $update_labtest = "UPDATE tblpatlabtest SET fldtestunit='SI', fldreportquanti=" . trim($testvalue) . ",fldreportquali=" . trim($testvalue) . ",fldstatus='Reported',fldtime_report=" . $reportedtime . ",xyz=0,fldsave_report=1 WHERE fldsampleid='" . $sampleid . "' and fldtestid='" . $testname . "'";
                                                if (trim($testvalue) != '') {
                                                    $result = DB::statement($update_labtest);



                                                    echo 'Query run successfully: ' . $update_labtest . '<br/>';
                                                }
                                            }
                                        }
                                    } else {
                                        echo 'No sample Id in patlabtest';
                                    }


                                    $subtest_query  = "select fldsubtest,fldid  from tblpatlabsubtest where fldsampleid='" . $sampleid . "' and fldsubtest='" . $testname . "'";

                                    echo $subtest_query . '<br/>';

                                    $check_subtest = DB::select(DB::raw($subtest_query));

                                    if ($check_subtest) {

                                        foreach ($check_subtest as $row2) {

                                            $subtest = $row2->fldsubtest;
                                            $test_fldid = $row2->fldid;


                                            if (trim($testvalue) != '') {
                                                $update_subtest = "UPDATE tblpatlabsubtest  SET  fldreport=" . trim($testvalue) . " WHERE fldid='" . $test_fldid . "' and fldsubtest='" . $subtest . "'";
                                                echo $update_subtest;

                                                DB::statement($update_subtest);
                                            }
                                        }
                                    } else {
                                        echo 'No sample Id in tblpatlabsubtest';
                                    }
                                }
                            }
                        }
                    }
                }
                $insert_dump_query = "UPDATE tbl_dump SET pat_lab_insert= 1 WHERE id ='" . $dumpid . "'";
                $resultdump = DB::statement($insert_dump_query);
                echo 'Query run successfully: ' . $insert_dump_query . '<br/>';
            }
        }
    }

    public function cobasInterfacing()
    {
        $mapping = DB::table('machine_map')->pluck('test', 'code');

        $machineName = "cobas";

        $tbltests = DB::table('tbltest')->pluck('fldtestid')->toArray();

        $tblsubtests = DB::table('tblsubtestquali')->pluck('fldsubtest')->toArray();

        $machineDump = DumpTable::where('pat_lab_insert', 0)->where('machinename', $machineName)->get();

        //LB77-5236----413**3.55
        if ($machineDump) {

            foreach ($machineDump as $dump) {

                $dumpid = $dump->id;

                $rowdata = explode("----", $dump->dumpdata);

                if (!empty($rowdata)) {

                    $sampleid = $rowdata[0];
                    echo 'Sample id ' . $sampleid . '<br>';

                    $test = explode("**", $rowdata[1]);

                    $testCode  = $test[0];
                    $testresult = $test[1];


                    $testname1 = trim($testCode);
                    $testvalue = trim($testresult);
                    $reportedtime = "CURRENT_TIMESTAMP";


                    $testlist = MachineMap::where('code', $testname1)->get();

                    if ($testlist) {
                        foreach ($testlist as $test) {
                            $testname = $test->test;

                            if (trim($testvalue) != '') {

                                $check_in_sampleid = "";
                                $check_in_sampleid = "select * from tblpatlabtest where fldstatus='Sampled'  and fldsampleid='" . $sampleid . "' and fldtestid='" . $testname . "'";

                                echo $check_in_sampleid . '<br/>';

                                $collected_sampled_data = DB::select(DB::raw($check_in_sampleid));


                                if (!empty($collected_sampled_data)) {
                                    foreach ($collected_sampled_data as $sampled_data) {
                                        if (!empty($sampled_data)) {

                                            $update_labtest = "UPDATE tblpatlabtest SET fldtestunit='SI', fldreportquanti=" . trim($testvalue) . ",fldreportquali=" . trim($testvalue) . ",fldstatus='Reported',fldtime_report=" . $reportedtime . ",xyz=0,fldsave_report=1 WHERE fldsampleid='" . $sampleid . "' and fldtestid='" . $testname . "'";
                                            if (trim($testvalue) != '') {
                                                $result = DB::statement($update_labtest);


                                                echo 'Query run successfully: ' . $update_labtest . '<br/>';
                                            }
                                        }
                                    }
                                } else {
                                    echo "No Sample id in patlabtest" . '<br/>';
                                }

                                $subtest_query  = "select fldsubtest,fldid  from tblpatlabsubtest where fldsampleid='" . $sampleid . "' and fldsubtest='" . $testname . "'";

                                echo $subtest_query . '<br/>';

                                $check_subtest = DB::select(DB::raw($subtest_query));

                                if ($check_subtest) {

                                    foreach ($check_subtest as $row2) {
                                        $subtest = $row2->fldsubtest;
                                        $test_fldid = $row2->fldid;

                                        if (trim($testvalue) != '') {
                                            $update_subtest = "UPDATE tblpatlabsubtest  SET  fldreport=" . trim($testvalue) . " WHERE fldid='" . $test_fldid . "' and fldsubtest='" . $subtest . "'";
                                            echo $update_subtest;

                                            DB::statement($update_subtest);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $insert_dump_query = "UPDATE tbl_dump SET pat_lab_insert= 1 WHERE id ='" . $dumpid . "'";
                $resultdump = DB::statement($insert_dump_query);
                echo 'Query run successfully: ' . $insert_dump_query . '<br/>';
            }
        }
    }

    public function biochemInterfacing()
    {
        $mapping = DB::table('machine_map')->pluck('test', 'code');

        $machineName = "Biochem_siemens";

        $tbltests = DB::table('tbltest')->pluck('fldtestid')->toArray();

        $tblsubtests = DB::table('tblsubtestquali')->pluck('fldsubtest')->toArray();

        $machineDump = DumpTable::where('pat_lab_insert', 0)->where('machinename', $machineName)->get();

        if ($machineName == "Biochem_siemens") {

            if ($machineDump) {

                foreach ($machineDump as $dump) {

                    $dumpid = $dump->id;

                    $rowdata = explode("----", $dump->dumpdata);
                    //LB77-5236----FT3**7.58----FT4**4.62----TSH**13.9

                    if (!empty($rowdata)) {


                        $sampleid = $rowdata[0];
                        echo 'Sample id ' . $sampleid . '<br>';


                        foreach ($rowdata as $rowKey => $rowValue) {

                            if ($rowKey != 0) {

                                if ($rowValue) {
                                    $test = explode("**", $rowValue);

                                    $testCode  = $test[0];
                                    $testresult = $test[1];


                                    $testname1 = trim($testCode);
                                    $testvalue = trim($testresult);
                                    $reportedtime = "CURRENT_TIMESTAMP";


                                    $testlist = MachineMap::where('code', $testname1)->get();
                                    if ($testlist) {
                                        foreach ($testlist as $test) {
                                            $testname = $test->test;
                                            echo 'Testname : ' . $testname;

                                            $check_in_sampleid = "";
                                            $check_in_sampleid = "select * from tblpatlabtest where fldstatus='Sampled'  and fldsampleid='" . $sampleid . "' and fldtestid='" . $testname . "'";

                                            echo $check_in_sampleid . '<br/>';

                                            $collected_sampled_data = DB::select(DB::raw($check_in_sampleid));

                                            if (!empty($collected_sampled_data)) {
                                                foreach ($collected_sampled_data as $sampled_data) {
                                                    if (!empty($sampled_data)) {


                                                        $update_labtest = "UPDATE tblpatlabtest SET fldtestunit='SI', fldreportquanti=" . trim($testvalue) . ",fldreportquali=" . trim($testvalue) . ",fldstatus='Reported',fldtime_report=" . $reportedtime . ",xyz=0,fldsave_report=1 WHERE fldsampleid='" . $sampleid . "' and fldtestid='" . $testname . "'";
                                                        if (trim($testvalue) != '') {
                                                            $result = DB::statement($update_labtest);

                                                            echo 'Query run successfully: ' . $update_labtest . '<br/>';
                                                        }
                                                    }
                                                }
                                            } else {
                                                echo 'No sample Id in patlabtest';
                                            }


                                            $subtest_query  = "select fldsubtest,fldid  from tblpatlabsubtest where fldsampleid='" . $sampleid . "' and fldsubtest='" . $testname . "'";

                                            echo $subtest_query . '<br/>';

                                            $check_subtest = DB::select(DB::raw($subtest_query));

                                            if ($check_subtest) {

                                                foreach ($check_subtest as $row2) {

                                                    $subtest = $row2->fldsubtest;
                                                    $test_fldid = $row2->fldid;


                                                    if (trim($testvalue) != '') {
                                                        $update_subtest = "UPDATE tblpatlabsubtest  SET  fldreport=" . trim($testvalue) . " WHERE fldid='" . $test_fldid . "' and fldsubtest='" . $subtest . "'";
                                                        echo $update_subtest;

                                                        DB::statement($update_subtest);
                                                    }
                                                }
                                            } else {
                                                echo 'No sample Id in tblpatlabsubtest';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $insert_dump_query = "UPDATE tbl_dump SET pat_lab_insert= 1 WHERE id ='" . $dumpid . "'";
                    $resultdump = DB::statement($insert_dump_query);
                    echo 'Query run successfully: ' . $insert_dump_query . '<br/>';
                }
            }
        }
    }


    public function machineInterfacingDepartment()
    {

        $mapping = DB::table('machine_map')->pluck('test', 'code');

        $machineName = '';

        $tbltests = DB::table('tbltest')->pluck('fldtestid')->toArray();
        $tblsubtests = DB::table('tblsubtestquali')->pluck('fldsubtest')->toArray();

        $machines = DumpTable::distinct('machinename')->get('machinename');

        //$limit = 100;
        if ($machines) {
            foreach ($machines as $machine) {

                $machineName = $machine->machinename;

                $machineDump = DumpTable::where('pat_lab_insert', 0)->where('machinename', $machineName)->get();



                if ($machineName == "hematology") {
                    //LB77-5224 SABIN----WBC**7.28----RBC**5.55----HGB**16.1----HCT**46.5
                    if ($machineDump) {
                        foreach ($machineDump as $dump) {
                            $dumpid = $dump->id;

                            $rowdata = explode("----", $dump->dumpdata);


                            $nameSampleId = explode(' ', $rowdata[0]);
                            $sampleid = $nameSampleId[0];

                            echo 'Sample id ' . $sampleid . '<br>';

                            foreach ($rowdata as $rowKey => $rowValue) {

                                if ($rowKey != 0) {

                                    if ($rowValue) {

                                        $testnameline = explode('**', $rowValue);

                                        $testname1 = trim($testnameline[0]); // code of the test
                                        $testvalue = trim($testnameline[1]); // value of the test

                                        $reportedtime = "CURRENT_TIMESTAMP";

                                        //checking if the code is available or not
                                        // if (isset($mapping[$testname1])) {
                                        //     $testname = $mapping[$testname1];
                                        // } else {
                                        //     $testname = $testname1;
                                        // }

                                        // DB::table('machine_map')->pluck('test', 'code');
                                        $testlist = MachineMap::where('code', $testname1)->get();
                                        // echo $testname1.' '.$sampleid;
                                        if ($testlist) {
                                            foreach ($testlist as $test) {
                                                $testname = $test->test;
                                                echo 'this is testname : ' . $testname;
                                                // if (in_array($testname, $tbltests)) {

                                                $check_in_sampleid = "";
                                                $check_in_sampleid = "select * from tblpatlabtest where fldstatus='Sampled'  and fldsampleid='" . $sampleid . "' and fldtestid='" . $testname . "'";

                                                echo $check_in_sampleid . '<br/>';

                                                $collected_sampled_data = DB::select(DB::raw($check_in_sampleid));

                                                //print_r($checkdatas);

                                                if (trim($testvalue) != '') {

                                                    $subtest_query  = "select fldsubtest,fldid  from tblpatlabsubtest where fldsampleid='" . $sampleid . "' and fldsubtest='" . $testname . "'";

                                                    echo $subtest_query . '<br/>';

                                                    $check_subtest = DB::select(DB::raw($subtest_query));
                                                    //print_r($check_subtest);
                                                    if ($check_subtest) {

                                                        foreach ($check_subtest as $row2) {
                                                            // print_r($row2);
                                                            $subtest = $row2->fldsubtest;
                                                            $test_fldid = $row2->fldid;


                                                            if (trim($testvalue) != '') {
                                                                $update_subtest = "UPDATE tblpatlabsubtest  SET  fldreport=" . trim($testvalue) . " WHERE fldid='" . $test_fldid . "' and fldsubtest='" . $subtest . "'";
                                                                echo $update_subtest;

                                                                DB::statement($update_subtest);
                                                            }
                                                        }
                                                    }
                                                }

                                                if (!empty($collected_sampled_data)) {
                                                    foreach ($collected_sampled_data as $sampled_data) {
                                                        if (!empty($sampled_data)) {


                                                            $update_labtest = "UPDATE tblpatlabtest SET fldtestunit='SI', fldreportquanti=" . trim($testvalue) . ",fldreportquali=" . trim($testvalue) . ",fldstatus='Reported',fldtime_report=" . $reportedtime . ",xyz=0,fldsave_report=1 WHERE fldsampleid='" . $sampleid . "' and fldtestid='" . $testname . "'";
                                                            if (trim($testvalue) != '') {
                                                                $result = DB::statement($update_labtest);

                                                                $insert_dump_query = "UPDATE tbl_dump SET pat_lab_insert= 1 WHERE id ='" . $dumpid . "'";
                                                                $resultdump = DB::statement($insert_dump_query);

                                                                echo 'Query run successfully: ' . $update_labtest . '<br/>';
                                                                echo 'Query run successfully: ' . $insert_dump_query . '<br/>';
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    echo "No Sample id" . '<br/>';
                                                }
                                                // } else {
                                                //   echo "No Test Name" . '<br/>';
                                                // }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }


                if ($machineName == "cobas") {
                    //LB77-5236----413**3.55
                    if ($machineDump) {

                        foreach ($machineDump as $dump) {

                            $dumpid = $dump->id;

                            $rowdata = explode("----", $dump->dumpdata);

                            if (!empty($rowdata)) {

                                $sampleid = $rowdata[0];
                                echo 'Sample id ' . $sampleid . '<br>';

                                $test = explode("**", $rowdata[1]);

                                $testCode  = $test[0];
                                $testresult = $test[1];


                                $testname1 = trim($testCode);
                                $testvalue = trim($testresult);
                                $reportedtime = "CURRENT_TIMESTAMP";


                                // if (isset($mapping[$testname1])) {
                                //     $testname = $mapping[$testname1];
                                // } else {
                                //     $testname = $testname1;
                                // }

                                $testlist = MachineMap::where('code', $testname1)->get();
                                if ($testlist) {
                                    foreach ($testlist as $test) {
                                        $testname = $test->test;


                                        if (in_array($testname, $tbltests)) {

                                            if (trim($testvalue) != '') {

                                                $subtest_query  = "select fldsubtest,fldid  from tblpatlabsubtest where fldsampleid='" . $sampleid . "' and fldsubtest='" . $testname . "'";

                                                echo $subtest_query . '<br/>';

                                                $check_subtest = DB::select(DB::raw($subtest_query));

                                                if ($check_subtest) {

                                                    foreach ($check_subtest as $row2) {
                                                        $subtest = $row2->fldsubtest;
                                                        $test_fldid = $row2->fldid;

                                                        if (trim($testvalue) != '') {
                                                            $update_subtest = "UPDATE tblpatlabsubtest  SET  fldreport=" . trim($testvalue) . " WHERE fldid='" . $test_fldid . "' and fldsubtest='" . $subtest . "'";
                                                            echo $update_subtest;

                                                            DB::statement($update_subtest);
                                                        }
                                                    }
                                                }
                                            }

                                            $check_in_sampleid = "";
                                            $check_in_sampleid = "select * from tblpatlabtest where fldstatus='Sampled'  and fldsampleid='" . $sampleid . "' and fldtestid='" . $testname . "'";

                                            echo $check_in_sampleid . '<br/>';

                                            $collected_sampled_data = DB::select(DB::raw($check_in_sampleid));
                                            //print_r($collected_sampled_data);

                                            if (!empty($collected_sampled_data)) {
                                                foreach ($collected_sampled_data as $sampled_data) {
                                                    if (!empty($sampled_data)) {


                                                        $test_fldid = $sampled_data->fldid;
                                                        $subtest_query  = "select fldsubtest,fldid  from tblpatlabsubtest where fldsampleid='" . $sampleid . "' and fldsubtest='" . $testname . "'";

                                                        echo $subtest_query . '<br/>';

                                                        $check_subtest = DB::select(DB::raw($subtest_query));

                                                        if ($check_subtest) {

                                                            foreach ($check_subtest as $row2) {
                                                                $subtest = $row2->fldsubtest;

                                                                if (trim($testvalue) != '') {
                                                                    $update_subtest = "UPDATE tblpatlabsubtest SET fldreport=" . trim($testvalue) . " WHERE fldtestid='" . $test_fldid . "' and fldsubtest='" . $subtest . "'";

                                                                    DB::statement($update_subtest);
                                                                }
                                                            }
                                                        }

                                                        $update_labtest = "UPDATE tblpatlabtest SET fldtestunit='SI', fldreportquanti=" . trim($testvalue) . ",fldreportquali=" . trim($testvalue) . ",fldstatus='Reported',fldtime_report=" . $reportedtime . ",xyz=0,fldsave_report=1 WHERE fldsampleid='" . $sampleid . "' and fldtestid='" . $testname . "'";
                                                        if (trim($testvalue) != '') {
                                                            $result = DB::statement($update_labtest);
                                                            $insert_dump_query = "UPDATE tbl_dump SET pat_lab_insert= 1 WHERE id ='" . $dumpid . "'";
                                                            $resultdump = DB::statement($insert_dump_query);

                                                            echo 'Query run successfully: ' . $update_labtest . '<br/>';
                                                            echo 'Query run successfully: ' . $insert_dump_query . '<br/>';
                                                        }
                                                    }
                                                }
                                            } else {
                                                echo "No Sample id" . '<br/>';
                                            }
                                        } else {
                                            echo "No Test Name" . '<br/>';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if ($machineName == "Biochem_siemens") {

                    if ($machineDump) {

                        foreach ($machineDump as $dump) {

                            $dumpid = $dump->id;

                            $rowdata = explode("----", $dump->dumpdata);
                            //LB77-5236----FT3**7.58----FT4**4.62----TSH**13.9

                            if (!empty($rowdata)) {


                                $sampleid = $rowdata[0];
                                echo 'Sample id ' . $sampleid . '<br>';


                                foreach ($rowdata as $rowKey => $rowValue) {

                                    if ($rowKey != 0) {

                                        if ($rowValue) {
                                            $test = explode("**", $rowValue);

                                            $testCode  = $test[0];
                                            $testresult = $test[1];


                                            $testname1 = trim($testCode);
                                            $testvalue = trim($testresult);
                                            $reportedtime = "CURRENT_TIMESTAMP";


                                            // if (isset($mapping[$testname1])) {
                                            //     $testname = $mapping[$testname1];
                                            // } else {
                                            //     $testname = $testname1;
                                            // }

                                            $testlist = MachineMap::where('code', $testname1)->get();
                                            if ($testlist) {
                                                foreach ($testlist as $test) {
                                                    $testname = $test->test;
                                                    if (in_array($testname, $tbltests)) {

                                                        if (trim($testvalue) != '') {

                                                            $subtest_query  = "select fldsubtest,fldid  from tblpatlabsubtest where fldsampleid='" . $sampleid . "' and fldsubtest='" . $testname . "'";

                                                            echo $subtest_query . '<br/>';

                                                            $check_subtest = DB::select(DB::raw($subtest_query));

                                                            if ($check_subtest) {

                                                                foreach ($check_subtest as $row2) {
                                                                    $subtest = $row2->fldsubtest;
                                                                    $test_fldid = $row2->fldid;

                                                                    if (trim($testvalue) != '') {
                                                                        $update_subtest = "UPDATE tblpatlabsubtest SET  fldreport=" . trim($testvalue) . " WHERE fldtestid='" . $test_fldid . "' and fldsubtest='" . $subtest . "'";

                                                                        DB::statement($update_subtest);
                                                                    }
                                                                }
                                                            }
                                                        }

                                                        $check_in_sampleid = "";
                                                        $check_in_sampleid = "select * from tblpatlabtest where fldstatus='Sampled'  and fldsampleid='" . $sampleid . "' and fldtestid='" . $testname . "'";

                                                        echo $check_in_sampleid . '<br/>';

                                                        $collected_sampled_data = DB::select(DB::raw($check_in_sampleid));
                                                        //print_r($collected_sampled_data);

                                                        if (!empty($collected_sampled_data)) {
                                                            foreach ($collected_sampled_data as $sampled_data) {
                                                                if (!empty($sampled_data)) {


                                                                    $test_fldid = $sampled_data->fldid;
                                                                    $subtest_query  = "select fldsubtest,fldid  from tblpatlabsubtest where fldtestid ='" . $test_fldid . "' ";

                                                                    echo $subtest_query . '<br/>';

                                                                    $check_subtest = DB::select(DB::raw($subtest_query));

                                                                    if ($check_subtest) {

                                                                        foreach ($check_subtest as $row2) {
                                                                            $subtest = $row2->fldsubtest;

                                                                            if (trim($testvalue) != '') {
                                                                                $update_subtest = "UPDATE tblpatlabsubtest  SET  fldreport=" . trim($testvalue) . " WHERE fldid='" . $test_fldid . "' and fldsubtest='" . $subtest . "'";
                                                                                echo $update_subtest;

                                                                                DB::statement($update_subtest);
                                                                            }
                                                                        }
                                                                    }
                                                                    if (trim($testvalue) != '') {
                                                                        $update_labtest = "UPDATE tblpatlabtest SET fldtestunit='SI', fldreportquanti=" . trim($testvalue) . ",fldreportquali=" . trim($testvalue) . ",fldstatus='Reported',fldtime_report=" . $reportedtime . ",xyz=0,fldsave_report=1 WHERE fldsampleid='" . $sampleid . "' and fldtestid='" . $testname . "'";

                                                                        $result = DB::statement($update_labtest);

                                                                        $insert_dump_query = "UPDATE tbl_dump SET pat_lab_insert= 1 WHERE id ='" . $dumpid . "'";
                                                                        $resultdump = DB::statement($insert_dump_query);

                                                                        echo 'Query run successfully: ' . $update_labtest . '<br/>';
                                                                        echo 'Query run successfully: ' . $insert_dump_query . '<br/>';
                                                                    }
                                                                }
                                                            }
                                                        } else {
                                                            echo "No Sample id" . '<br/>';
                                                        }
                                                    } else {
                                                        echo "No Test Name" . '<br/>';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }



    public function checkCodes($sample_id)
    {
        $alltest = PatLabTest::where('fldstatus', 'Sampled')->where('fldsampleid', $sample_id)->get();
        if (!empty($alltest)) {
            $codes = array();
            foreach ($alltest as $test) {
                $user = DB::table('tblencounter')
                    ->join('tblpatientinfo', 'tblpatientinfo.fldpatientval', '=', 'tblencounter.fldpatientval')
                    ->select('tblpatientinfo.fldptnamefir', 'tblpatientinfo.fldptnamelast', 'tblpatientinfo.fldmiddlename', 'tblpatientinfo.fldptsex', 'tblencounter.fldrank', 'tblpatientinfo.fldptbirday')
                    ->where('tblencounter.fldencounterval', $test->fldencounterval)
                    ->first();

                $codes['name'] =  $user->fldrank . ' ' . ucwords($user->fldptnamefir) . ' ' . ucwords($user->fldmiddlename) . ' ' . ucwords($user->fldptnamelast);
                $bday = $user->fldptbirday;
                $codes['age'] = \Carbon\Carbon::parse($bday ?? "")->age . ' Years'; //$user->fldptbirday;
                $codes['sex'] = $user->fldptsex;
                $machine_map = MachineMap::select('code')->where('test', $test->fldtestid)->first();
                if (!empty($machine_map)) {
                    $codes['codes'][] = $machine_map->code;
                }
            }
            print_r(json_encode($codes));
        }
    }


    public function machineCodes($sample_id)
    {
        //patientname| age|sex|Machinename-testname1-testcode1**machinename-testname2-testcode2**
        $alltest = PatLabTest::where('fldstatus', 'Sampled')->where('fldsampleid', $sample_id)->get();

        if (!empty($alltest)) {
            $codes = '';
            $codes_array = '';
            $final_codes = '';
            $personal_detail = "Sample not found";
            foreach ($alltest as $test) {

                $user = DB::table('tblencounter')
                    ->join('tblpatientinfo', 'tblpatientinfo.fldpatientval', '=', 'tblencounter.fldpatientval')
                    ->select('tblpatientinfo.fldptnamefir', 'tblpatientinfo.fldptnamelast', 'tblpatientinfo.fldmiddlename', 'tblpatientinfo.fldptsex', 'tblencounter.fldrank', 'tblpatientinfo.fldptbirday')
                    ->where('tblencounter.fldencounterval', $test->fldencounterval)
                    ->first();

                $name =  $user->fldrank . ' ' . ucwords($user->fldptnamefir) . ' ' . ucwords($user->fldmiddlename) . ' ' . ucwords($user->fldptnamelast);
                // $bday = $user->fldptbirday;
                // $age = \Carbon\Carbon::parse($bday  ?? "")->age . ' Years'; //$user->fldptbirday;
                $age = $user->fldagestyle;
                $sex = $user->fldptsex;
                $personal_detail = $name . '|' . $age . '|' . $sex . '|';
                $machine_map = MachineMap::select('code', 'machinename', 'test')->where('test', $test->fldtestid)->first();
                if (!empty($machine_map)) {

                    $codes = $machine_map->machinename . '-' . $machine_map->test . '-' . $machine_map->code . '**'; //'test-look-sample**';

                }

                $codes_array .= $codes;
            }
            $final_codes =  $personal_detail . $codes_array;
            print_r($final_codes);
        }
    }
}

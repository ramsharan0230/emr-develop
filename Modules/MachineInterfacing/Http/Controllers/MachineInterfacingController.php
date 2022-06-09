<?php

namespace App\Http\Controllers;

use App\DumpTable;
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


    public function machineInterfacingDepartment()
    {

        $mapping = DB::table('machine_map')->pluck('test', 'code');
        $machineName = '';
        $tbltests = DB::table('tbltest')->pluck('fldtestid')->toArray();
        $machines = DumpTable::distinct('machinename')->get('machinename');
        $limit = 3000;
        if ($machines) {
            foreach ($machines as $machine) {
                $machineName = $machine->machinename;
                $machineDump = DumpTable::where('pat_lab_insert', 0)->where('machinename', $machineName)->limit($limit)->get();

                if ($machineName == "Biochem_siemens") {

                    if ($machineDump) {
                        foreach ($machineDump as $dump) {
                            $rowdata = preg_split("/[\t]/", $dump->dump);
                            if ($rowdata) {
                                //  print_r($rowdata); echo '<pre>';
                                $sampleid = trim($rowdata[0], '"');
                                $testvalue = trim($rowdata[7], '"');
                                $testname1 = trim($rowdata[3], '"');

                                $reported_date = $rowdata[14];
                                if ($reported_date == '"---"' || $reported_date == "") {
                                    $reportedtime = 'CURRENT_TIMESTAMP';
                                } else {
                                    $reportedtime = strtotime("$reported_date");
                                }

                                //print_r($reportedtime); echo '<pre>';
                                if (isset($mapping[$testname1])) {
                                    $testname = $mapping[$testname1];
                                } else {
                                    $testname = $testname1;
                                }


                                $search_array = array_map('strtolower', $tbltests);

                                // echo '<pre>';
                                // echo $testname;
                                // echo '</pre>';
                                if (in_array(strtolower($testname), $search_array)) {
                                    $sql4 = "";
                                    $checksampleid = "select fldtestid from tblpatlabtest where fldstatus='Sampled'  and fldsampleid='" . $sampleid . "'";
                                    if ($checksampleid) {
                                        $pattest = "select fldtestid from tblpatlabtest where fldstatus='Sampled'  and fldsampleid='" . $sampleid . "' and fldtestid='$testname'";
                                        if (!$pattest) {
                                            $subpattest = "";
                                        } else {
                                            $checkdata = DB::select(DB::raw($sql4));

                                            //print_r($checkdata);
                                            if ($checkdata) {
                                                $sql = "UPDATE tblpatlabtest SET fldreportquanti=" . $testvalue . ",fldreportquali=" . $testvalue . ",fldstatus='Reported',fldtime_report=" . $reportedtime . ",xyz=0,fldsave_report=1,fldcomp_report='comp04',fldtestunit='SI'" . " WHERE fldsampleid='" . $sampleid . "' and fldtestid='" . $testname . "'";

                                                $result = DB::statement($sql);
                                                // $sqldump = "UPDATE tbl_dump SET pat_lab_insert= 1 WHERE id ='" . $dump->id . "'";
                                                $resultdump = DB::statement($sqldump);
                                                echo 'Query run successfully: ' . $sql . '<br/>';
                                                echo 'Query run successfully: ' . $sqldump . '<br/>';
                                            }

                                            //print_r($checkdata);
                                            if ($checkdata) {
                                                $sql = "UPDATE tblpatlabtest SET fldreportquanti=" . $testvalue . ",fldreportquali=" . $testvalue . ",fldstatus='Reported',fldtime_report=" . $reportedtime . ",xyz=0,fldsave_report=1,fldcomp_report='comp04',fldtestunit='SI'" . " WHERE fldsampleid='" . $sampleid . "' and fldtestid='" . $testname . "'";

                                                $result = DB::statement($sql);
                                                $sqldump = "UPDATE tbl_dump SET pat_lab_insert= 1 WHERE id ='" . $dump->id . "'";
                                                $resultdump = DB::statement($sqldump);
                                                echo 'Query run successfully: ' . $sql . '<br/>';
                                                echo 'Query run successfully: ' . $sqldump . '<br/>';
                                            }

                                        }
                                    }


                                } else {
                                    echo "No sample Id" . '<br/>';
                                }
                            }
                        }
                    }
                } else if ($machineName == "hematology") {

                    if ($machineDump) {
                        foreach ($machineDump as $dump) {
                            $dumpid = $dump->id;

                            $rowdata = explode("----", $dump->dump);


                            $nameSampleId = explode(' ', $rowdata[0]);
                            $sampleid = $nameSampleId[0];

                            foreach ($rowdata as $rowKey => $rowValue) {
                                if ($rowKey != 0) {
                                    if ($rowValue) {
                                        // $sampleid = trim($rowValue[0]);
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


                                        if (in_array($testname, $tbltests)) {
                                            $sql4 = "";
                                            $sql4 = "select fldtestid from tblpatlabtest where fldstatus='Sampled'  and fldsampleid='" . $sampleid . "' and fldtestid='" . $testname . "'";

                                            echo $sql4 . '<br/>';

                                            $checkdata = DB::select(DB::raw($sql4));



                                            if ($checkdata) {
                                                $sqlsub  = "select fldsubtest  from tblpatlabsubtest where fldtestid ='" . $checkdata->fldid . "' ";

                                                echo $sqlsub . '<br/>';

                                                $checksubdata = DB::select(DB::raw($sqlsub));

                                                if($checksubdata){
                                                    $sqlsub = "UPDATE tblpatlabsubtest SET fldreport=" . trim($testvalue) . ",fldstatus='Reported',fldtime_report=" . $reportedtime . " WHERE fldtestid='" . $checkdata->fldid . "' and fldsubtest='" . $checkdata->fldsubtest . "'";

                                                    $resultsqlsub = DB::statement($sqlsub);
                                                }

                                                $sql = "UPDATE tblpatlabtest SET fldreportquanti=" . trim($testvalue) . ",fldreportquali=" . trim($testvalue) . ",fldstatus='Reported',fldtime_report=" . $reportedtime . ",xyz=0,fldsave_report=1,fldcomp_report='comp04',fldtestunit='SI'" . " WHERE fldsampleid='" . $sampleid . "' and fldtestid='" . $testname . "'";

                                                $result = DB::statement($sql);

                                                $sqldump = "UPDATE tbl_dump SET pat_lab_insert= 1 WHERE id ='" . $dump->id . "'";
                                                $resultdump = DB::statement($sqldump);

                                                echo 'Query run successfully: ' . $sql . '<br/>';
                                                echo 'Query run successfully: ' . $sqldump . '<br/>';



                                                echo 'Query run successfully: ' . $sql . '<br/>';


                                            }

                                            if ($checksubdata) {
                                                $sql = "UPDATE tblpatlabtest SET fldreportquanti=" . trim($testvalue) . ",fldreportquali=" . trim($testvalue) . ",fldstatus='Reported',fldtime_report=" . $reportedtime . ",xyz=0,fldsave_report=1,fldcomp_report='comp04',fldtestunit='SI'" . " WHERE fldsampleid='" . $sampleid . "' and fldtestid='" . $testname . "'";

                                                $result = DB::statement($sql);

                                                $sqldump = "UPDATE tbl_dump SET pat_lab_insert= 1 WHERE id ='" . $dump->id . "'";
                                                $resultdump = DB::statement($sqldump);

                                                echo 'Query run successfully: ' . $sql . '<br/>';
                                                echo 'Query run successfully: ' . $sqldump . '<br/>';



                                                echo 'Query run successfully: ' . $sql . '<br/>';


                                            }


                                            echo 'Query run successfully: ' . $sql . '<br/>';
                                        }

                                        echo "No sample Id" . '<br/>';
                                    }
                                }
                            }
                        }
                        $sqldump = "UPDATE tbl_dump SET pat_lab_insert= 1 WHERE id ='" . $dumpid . "'";
                        $resultdump = DB::statement($sqldump);
                        echo 'Query run successfully: ' . $sqldump . '<br/>';

                    }
                }
            }
        }
    }
}

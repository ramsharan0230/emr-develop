<?php


namespace App\Utils;


use App\Exam;
use App\Pathocategory;
use App\Radio;
use App\Sampletype;
use App\Sysconst;
use App\Test;

class Diagnosishelpers
{

    public static function getAlltheTests() {
        $test = Test::orderBy('fldtestid')->paginate(100);

        return $test;
    }

    public static function getPathoCategory( $category = NULL) {

        $pathocategory = Pathocategory::where('fldcategory', $category)->get();

        return $pathocategory;
    }

    public static function getallSampletype() {
        $sampletype = Sampletype::all();

        return $sampletype;
    }

    public static function getallSysConstant( $sysconstcategory = NULL) {
        $sysconst = Sysconst::where('fldcategory', $sysconstcategory)->get();

        return $sysconst;
    }

    public static function getLatestAddedCategory() {
        $latestcategory = Pathocategory::where('fldcategory', 'Test')->orderBy('fldid', 'DESC')->first();

        return $latestcategory;
    }

    public static function getAllExams() {
        $exams = Exam::orderBy('fldexamid')->paginate(100);

        return $exams;
    }

    public static function getAlltheRadio() {
        $radio = Radio::orderBy('fldexamid')->get();

        return $radio;
    }
}

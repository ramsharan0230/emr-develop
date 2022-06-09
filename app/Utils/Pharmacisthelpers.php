<?php


namespace App\Utils;


use App\Drug;
use App\HospitalDepartment;
use App\Locallabel;
use App\MacAccess;
use App\MedGroup;
use App\ProductGroup;

class Pharmacisthelpers
{


    public static function getLocalLabel() {

        $locallabels = Locallabel::all();

        return $locallabels;

    }

    public static function getFldroutefromDrug($flddrug = NULL) {

        if($flddrug) {
            $fldroute = Drug::select('fldroute')->where('flddrug', $flddrug)->first();
        } else {
            $fldroute = '';
        }


        return ($fldroute) ? $fldroute->fldroute : '';
    }

    public static function getAllMedGroups() {

        $medgroups = MedGroup::orderBy('fldmedgroup', 'ASC')->get();

        return $medgroups;
    }

    public static function getAllPrdoctgroupsFromMedGroup($fldmedgroup) {
        $productgroups = ProductGroup::where('fldmedgroup', $fldmedgroup)->orderBy('fldid', 'ASC')->get();

        return $productgroups;
    }

    public static function getAllComp() {
        $departments = HospitalDepartment::select('fldcomp','name')->get();

       return $departments;
    }

    public static function convertunicodecharatertopreeti($x){
        switch($x)
        {
            case " ":
                return " ";
            case "अ":
                return "c";
            case "आ":
                return "cf";
            case "ा":
                return "f";
            case "इ":
                return "O";
            case "ई":
                return "O{";
            case "र्":
                return "{";
            case "उ":
                return "p";
            case "ए":
                return "P";
            case "े":
                return "]";
            case "ै":
                return "}";
            case "ो":
                return "f]";
            case "ौ":
                return "f}";
            case "ओ":
                return "cf]";
            case "औ":
                return "cf}";
            case "ं":
                return "+";
            case "ँ":
                return "F";
            case "ि":
                return "l";
            case "ी":
                return "L";
            case "ु":
                return "'";
            case "ू":
                return '"';
            case "क":
                return "s";
            case "ख":
                return "v";
            case "ग":
                return "u";
            case "घ":
                return "3";
            case "ङ":
                return "ª";
            case "च":
                return "r";
            case "छ":
                return "5";
            case "ज":
                return "h";
            case "झ":
                return "´";
            case "ञ":
                return "`";
            case "ट":
                return "6";
            case "ठ":
                return "7";
            case "ड":
                return "8";
            case "ढ":
                return "9";
            case "ण":
                return "0f";
            case "त":
                return "t";
            case "थ":
                return "y";
            case "द":
                return "b";
            case "ध":
                return "w";
            case "न":
                return "g";
            case "प":
                return "k";
            case "फ":
                return "km";
            case "ब":
                return "a";
            case "भ":
                return "e";
            case "म":
                return "d";
            case "य":
                return "o";
            case "र":
                return "/";
            case "रू":
                return "?";
            case "ृ":
                return "[";
            case "ल":
                return "n";
            case "व":
                return "j";
            case "स":
                return ";";
            case "श":
                return "z";
            case "ष":
                return "if";
            case "ज्ञ":
                return "1";
            case "ह":
                return "x";
            case "१":
                return "!";
            case "२":
                return "@";
            case "३":
                return "#";
            case "४":
                return "$";
            case "५":
                return "%";
            case "६":
                return "^";
            case "७":
                return "&";
            case "८":
                return "*";
            case "९":
                return "(";
            case "०":
                return ")";
            case "।":
                return ".";
            case "्":
                return "\\";
            case "ऊ":
                return "pm";
            case "-":
                return " ";
            case "(":
                return "-";
            case ")":
                return "_";
        }
    }
}

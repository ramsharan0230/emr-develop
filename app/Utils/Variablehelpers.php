<?php


namespace App\Utils;


use App\BodyFluid;
use App\EthnicGroup;
use App\PatientInfo;
use App\Surname;
use Illuminate\Support\Facades\DB;

class Variablehelpers
{

    public static function getAllBodyfluids()
    {

        $bodyfluids = BodyFluid::orderBy('fldfluid', 'ASC')->get();

        return $bodyfluids;
    }

    public static function getAllEthnicGroups()
    {

        $ethnicgroups = EthnicGroup::select('fldgroupname')->distinct()->orderBy('fldgroupname', 'ASC')->get();

        return $ethnicgroups;
    }

    public static function getAllSurnames()
    {

        $surnames = Surname::orderBy('flditem', 'ASC')->get();

        return $surnames;
    }

    public static function getDuplicateitems()
    {

        $duplicates = DB::table('tblethnicgroup')
            ->select('flditemname', DB::raw('COUNT(*) as `count`'))
            ->groupBy('flditemname')

            ->get();

        return $duplicates;
    }

    public static function getMissingSurnameinEthicgroupfrompatientInfo()
    {

        $missing = DB::select('select distinct(fldptnamelast) as SurName FROM tblpatientinfo where fldptnamelast NOT IN(select flditemname from tblethnicgroup) ORDER BY fldptnamelast ASC');

        return $missing;
    }
}

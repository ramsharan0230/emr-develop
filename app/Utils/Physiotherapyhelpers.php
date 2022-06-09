<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 6/10/2021
 * Time: 9:05 AM
 */

namespace App\Utils;


use App\ExamGeneral;
use App\PatFindings;

class Physiotherapyhelpers
{
    public static function getPatFindings($encounter_id = NULL) {
        $patfindings = PatFindings::where([
            ['fldencounterval', $encounter_id],
            ['fldsave', 1],
        ])->whereIn('fldtype', [
            'Provisional Diagnosis',
            'Final Diagnosis'
        ])->select('fldcode', 'fldid', 'fldtype')->get();

        return $patfindings;
    }

    public static function getExamgeneral($encounter_id = NULL, $fldinput = NULL) {

        $examgeneral = ExamGeneral::where([['fldencounterval', $encounter_id], ['fldinput', $fldinput], ['flditem', 'physiotherapy']])->orderBy('fldid', 'DESC')->first();

        return $examgeneral;
    }
}
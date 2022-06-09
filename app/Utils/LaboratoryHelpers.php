<?php
namespace App\Utils;

class LaboratoryHelpers
{
    public static function formatCultureReport($allSubtest, $formatCode = 2)
    {
        $returnTable = "";
        if ($formatCode == 1) {
            $returnTable .= "<table style='width: 100%;' class='content-body test-content'><tbody>";
            foreach ($allSubtest as $subtest) {
                $returnTable .= "<tr><td class='td-align-top'><strong>" . $subtest->fldsubtest . "</strong></td>";
                if ($subtest->subtables->isNotEmpty()) {
                    $returnTable .= "<td><table style='width: 100%;' class='content-body test-content'><tbody>";
                    foreach ($subtest->subtables as $subtable) {
                        $returnTable .= "<tr>";
                        $returnTable .= "<td class='td-width'>" . $subtable->fldvariable . "</td>";
                        $returnTable .= "<td class='td-width'>" . $subtable->fldvalue . "</td>";
                        $returnTable .= "<td class='td-width'>" . $subtable->fldcolm2 . "</td>";
                        $returnTable .= "</tr>";
                    }
                    $returnTable .= "</tbody></table></td>";
                } else {
                    $returnTable .= "<td>" . $subtest->fldreport . "</td>";
                }
                $returnTable .= "</tr>";
                $returnTable .= "<tr><td colspan='2'>&nbsp;</td></tr>";
            }
            $returnTable .= "</tbody></table>";
        } elseif ($formatCode == 2) {
            $returnTable .= "<table style='width: 100%;' class='content-body test-content'><tbody>";
            foreach ($allSubtest as $subtest) {
                $returnTable .= "<tr><td class='td-align-top'><strong>" . $subtest->fldsubtest . "</strong>";
                if ($subtest->subtables->isNotEmpty()) {
                    $subtableData = $subtest->subtables->toArray();
                    $formatedSensitivity = [];
                    foreach ($subtableData as $subtable) {
                        $fldvariable = $subtable['fldvariable'];
                        $fldvariable .=  ($subtable['fldcolm2']) ? " [{$subtable['fldcolm2']}]" : "";
                        $formatedSensitivity[$subtable['fldvalue']][] = $fldvariable;
                    }

                    $returnTable .= "<div style='width: 100%;'>";
                    foreach ($formatedSensitivity as $sensitivity => $allValues) {
                        $returnTable .= "<div class='td-width'>";
                        $returnTable .= "<p>{$sensitivity}</p>";
                        $returnTable .= "<ul>";
                        foreach ($allValues as $value) {
                            $returnTable .= "<li>{$value}</li>";
                        }
                        $returnTable .= "</ul>";
                        $returnTable .= "</div>";
                    }
                    $returnTable .= "</div>";
                } else {
                    $returnTable .= "<br>" . $subtest->fldreport;
                }
                $returnTable .= "</td></tr>";
                $returnTable .= "<tr><td colspan='2'>&nbsp;</td></tr>";
            }
            $returnTable .= "</tbody></table>";

            
        }

        return $returnTable;
    }

    public static function getRefranceRange($testid, $subtestid)
    {
        $fldreference = \App\TestQuali::select('fldreference')->where([
            'fldtestid' => $testid,
            'fldsubtest' => $subtestid,
        ])->where(function($query) {
            $query->where('fldtanswertype', 'Text Reference')
                ->orWhere('fldtanswertype', 'Percent Sum')
                ->orWhere('fldtanswertype', 'Text Addition');
        })->first();
        if ($fldreference && $fldreference->fldreference == NULL) {
            $fldreference = \App\SubTestQuali::select('fldanswer')->where([
                'fldtestid' => $testid,
                'fldsubtest' => $subtestid,
            ])->where(function($query) {
                $query->where('fldanswertype', 'Text Reference')
                    ->orWhere('fldanswertype', 'Percent Sum')
                    ->orWhere('fldanswertype', 'Text Addition');
            })->first();
            return $fldreference ? $fldreference->fldanswer : '';
        }
        return $fldreference ? $fldreference->fldreference : '';
    }

    public static function getQuantitativeTestLimit($testid, $gender, $agerange = NULL)
    {
        $testlimit = \App\TestLimit::where('fldtestid', $testid)
            ->where(function($query) use ($gender) {
                $query->orWhere('fldptsex', $gender)->orWhere('fldptsex', 'Both Sex');
            })->where(function($query) use ($agerange) {
                if ($agerange)
                    $query->orWhere('fldagegroup', $agerange);
                $query->orWhere('fldagegroup', 'All Age');
            })->first();

        return $testlimit ? ("{$testlimit->fldsilow} - {$testlimit->fldsihigh} {$testlimit->fldsiunit}") : '';
    }
}

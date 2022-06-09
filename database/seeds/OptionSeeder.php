<?php

use App\Utils\Options;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = array(
            "content_1"  => 'Advice on Discharge',
            "content_2"  => 'Bed Transitions',
            "content_3"  => 'Cause of Admission',
            "content_4"  => 'Clinical Findings',
            "content_5"  => 'Clinical Notes',
            "content_12" => 'Condition at Discharge',
            "content_14" => 'Consultations',
            "content_15" => 'Course of Treatment',
            "content_16" => 'Delivery Profile',
            "content_17" => 'Demographics',
            "content_18" => 'Discharge examinations',
            "content_19" => 'Discharge Medication',
            "content_20" => 'Drug Allergy',
            "content_21" => 'Equipments Used',
            "content_22" => 'Essential examinations',
            "content_23" => 'Extra Procedures',
            "content_24" => 'Final Diagnosis',
            "content_25" => 'IP Monitoring',
            "content_26" => 'Initial Planning',
            "content_27" => 'Investigation Advised',
            "content_28" => 'Laboratory Tests',
            "content_29" => 'Major Procedures',
            "content_30" => 'Medication History',
            "content_31" => 'Medication Used',
            "content_32" => 'Minor Procedures',
            "Name"       => 'OPD Sheet',
            "content_34" => 'Occupational History',
            "content_35" => 'Personal History',
            "content_36" => 'Planned Procedures',
            "content_37" => 'Prominent Symptoms',
            "content_38" => 'Provisional Diagnosis',
            "content_39" => 'Radiological Findings',
            "content_40" => 'Social History',
            "content_41" => 'Structured examinations',
            "content_42" => 'Surgical History',
            "content_43" => 'Therapeutic Planning',
            "content_44" => 'Treatment Advised',
            "content_33" => 'Triage examinations',
            "HeaderType" => 'True',
            "BodyType"   => 'True',
            "FooterType" => 'True',
        );
        Options::update('opd_pdf_options', serialize($array));
    }
}

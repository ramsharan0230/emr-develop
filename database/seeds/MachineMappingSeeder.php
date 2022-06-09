<?php

use Illuminate\Database\Seeder;

class MachineMappingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $machingData = [
            'FT3' => 'Free Triiodothyronine',
            'FT4' => 'Free thyroxine',
            'TSH' => 'Thyyroid Stimulating Hormone',
            'PSA' => 'Prostate Specific Antigen',
            'Glu' => 'Glucose',
            'Ure' => 'Urea',
            'Crea' => 'Creatinine',
            'ALP' => 'Alkaline Phosphate',
            'ALT' => 'Alanine Transaminase',
            'SGPT' => 'Serum Glutamic Pyruvic Transaminase',
            'AST' => 'Aspartate transaminase',
            'SGOT' => 'serum glutamic-oxaloacetic transaminase',
            'TSB' => 'Total serum bilirubin',
            'DSB' => 'Direct Serum bilirubin',
            'BBT' => 'Bilirubin Total',
            'BBD' => 'Bilirubin Direct',
            'S.ALB' => 'Serum Albumin',
            'TP' => 'Total Protein',
            'GGT' => 'Gamma-GT',
            'CK-MB' => 'Creatine Kinase -MB',
            'CK-NAC' => 'Creatine Kinase -N',
            'CALL++' => 'Calcium',
            'IRON' => 'Iron',
            'AMY' => 'Amylase',
            'LIP' => 'Lipase',
            'TG' => 'Triglyceride',
            'CHOL' => 'Cholelsterol',
            'HDL' => 'High Density Lipoprotein',
            'LDL' => 'low Density Lipoprotein ',
            'LDH' => 'Lactate Dehydrogenase',
            'MAG' => 'Magnesium',
            'U/A' => 'Uric Acid',
            'FERIT' => 'Ferritin'
        ];
        \App\MachineMap::truncate();
        foreach ($machingData as $key => $val) {
            \App\MachineMap::create(['code' => $key, 'test' => $val]);
        }
    }
}

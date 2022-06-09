<?php

namespace Modules\Pathology\Http\Controllers;

use App\Pathocategory;
use App\Symptoms;
use App\Syndromes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;

class PathologyController extends Controller
{
   public function getSymptoms()
   {
      $data['symptoms'] = Symptoms::select('fldsymptom', 'fldcategory', 'fldsymdetail')->get();
      $data['symptomsCategories'] = Pathocategory::where('fldcategory', 'Symptom')->select('flclass')->get();
      return view('pathology::symptoms', $data);
   }

   public function getSyndromes()
   {
      $diagnocat = $this->getInitialDiagnosisCategory();
      $data['icd_group'] = $diagnocat;
      $data['syndromesCategories'] = Pathocategory::where('fldcategory', 'Syndrome')->select('flclass')->orderBy('fldid', 'DESC')->get();
      return view('pathology::syndromes', $data);
   }

   // insert syndromes variables
   public function insertVariableSyndrome(Request $request)
   {
      try{
         $checkifexist = Pathocategory::where('flclass', $request->flclass)->first();
         if($checkifexist != null){
             return response()->json([
                'status'=> FALSE,
                'message' => 'Symptom Already Exists Search The List.',
            ]);
         }else{
            \DB::table('tblpathocategory')->insert([
               'flclass' => $request->flclass,
               'fldcategory' => 'Syndrome'
            ]);
            return response()->json([
               'status'=> TRUE,
               'message' => 'Successfully Added Variable.',
            ]);
         }
      } catch (Exception $e) {
         return response()->json([
             'status'=> FALSE,
             'message' => 'Failed to Add Variable.',
         ]);
      }
   }

   // get syndromes variables
   public function getVariableSyndrome()
   {
      $get_related_syndrome_variables = Pathocategory::where('fldcategory', 'Syndrome')->select('fldid', 'flclass')->orderBy('fldid', 'DESC')->get();
      return response()->json($get_related_syndrome_variables);
   }

   // delete syndromes variables
   public function deleteVariableSyndrome(Request $request)
   {
      try{
         if($request->fldid != null)
         {
            Pathocategory::where(['fldcategory' => 'Syndrome', 'fldid' => $request->fldid])->delete();
            return response()->json([
               'status'=> TRUE,
               'message' => 'Successfully Deleted Variable.',
            ]);
         }
         return response()->json([
             'status'=> FALSE,
             'message' => 'Please Select Variable To Delete.',
         ]);
      } catch (Exception $e) {
         return response()->json([
             'status'=> FALSE,
             'message' => 'Failed to Delete Variable.',
         ]);
      }
   }

   // ICD Group
   public function getInitialDiagnosisCategory()
    {
      try {
            $handle = fopen(storage_path('upload/icd10cm_order.csv'), 'r');
            $data   = [];
            while ($csvLine = fgetcsv($handle, 1000, ";")) {
                if (isset($csvLine[1]) && strlen($csvLine[1]) == 3) {
                    $data[] = [
                        'code' => trim($csvLine[1]),
                        'name' => trim($csvLine[3]),
                    ];
                }
            }
            //sort($data);
            usort($data, function ($a, $b) {
                return $a['code'] <=> $b['code'];
            });
            // dd($data);
            return $data;
        } catch (\Exception $exception) {
            /*return response()->json(['status' => 'error', 'data' => []]);*/
            return [];
        }
   }

   // Syndrome Insert
   public function insertSundrome(Request $request)
   {
      try{
        $checkifexist = Syndromes::where('fldsyndrome', $request->fldsyndrome)->first();
        if($checkifexist != null){
            return response()->json([
               'status'=> FALSE,
               'message' => 'Syndrome Already Exists Search The List.',
           ]);
        }else{

           if($request->fldsymcode == null){
              $fldsymcode = 'Others';
           } else{
              $fldsymcode = $request->fldsymcode;
           }

           Syndromes::insert([
              'fldsyndrome' => $request->fldsyndrome,
              'fldcategory' => $request->fldcategory,
              'fldsymcode' => $fldsymcode
           ]);

           return response()->json([
              'status'=> TRUE,
              'message' => 'Successfully Added Syndrome.',
           ]);
        }
      } catch (Exception $e) {
         return response()->json([
             'status'=> FALSE,
             'message' => 'Failed to Add Syndrome.',
         ]);
      }
   }

   // Syndrome Delete
   public function deleteSundrome(Request $request)
   {
      try{
         if($request->fldsyndrome != null)
         {
            Syndromes::where('fldsyndrome', $request->fldsyndrome)->delete();
            return response()->json([
               'status'=> TRUE,
               'message' => 'Successfully Deleted Syndrome.',
            ]);
         }
         return response()->json([
             'status'=> FALSE,
             'message' => 'Please Select Syndrome To Delete.',
         ]);
      } catch (Exception $e) {
         return response()->json([
             'status'=> FALSE,
             'message' => 'Failed to Delete Syndrome.',
         ]);
      }
   }

   // get Syndrome
   public function getSyndrome()
   {
      $category = Input::get('fldcategory');
      $get_related_syndrome_data = Syndromes::where('fldcategory', $category)->select('fldsyndrome', 'fldsymcode')->get();
      return response()->json($get_related_syndrome_data);
   }

   // symptoms
   // insert syndromes variables
   public function insertVariableSymptom(Request $request)
   {
      try{
         $checkifexist = Pathocategory::where('flclass', $request->flclass)->first();
         if($checkifexist != null){
             return response()->json([
                'status'=> FALSE,
                'message' => 'Symptom Already Exists Search The List.',
            ]);
         }else{
            \DB::table('tblpathocategory')->insert([
               'flclass' => $request->flclass,
               'fldcategory' => 'Symptom'
            ]);
            return response()->json([
               'status'=> TRUE,
               'message' => 'Successfully Added Variable.',
            ]);
         }
      } catch (Exception $e) {
         return response()->json([
             'status'=> FALSE,
             'message' => 'Failed to Add Variable.',
         ]);
      }
   }

   // get syndromes variables
   public function getVariableSymptom()
   {
      $get_related_syndrome_variables = Pathocategory::where('fldcategory', 'Symptom')->select('fldid', 'flclass')->get();
      return response()->json($get_related_syndrome_variables);
   }

   // delete syndromes variables
   public function deleteVariableSymptom(Request $request)
   {
      try{
         if($request->fldid != null)
         {
            Pathocategory::where(['fldcategory' => 'Symptom', 'fldid' => $request->fldid])->delete();
            return response()->json([
               'status'=> TRUE,
               'message' => 'Successfully Deleted Variable.',
            ]);
         }
         return response()->json([
             'status'=> FALSE,
             'message' => 'Please Select Variable To Delete.',
         ]);
      } catch (Exception $e) {
         return response()->json([
             'status'=> FALSE,
             'message' => 'Failed to Delete Variable.',
         ]);
      }
   }

   // delete syndromes
   public function deleteSymptom(Request $request)
   {
      try{
         if($request->fldsymptom != null)
         {
            Symptoms::where('fldsymptom', $request->fldsymptom)->delete();
            return response()->json([
               'status'=> TRUE,
               'message' => 'Successfully Deleted Symptom.',
            ]);
         }
         return response()->json([
             'status'=> FALSE,
             'message' => 'Please Select Symptom To Delete.',
         ]);
      } catch (Exception $e) {
         return response()->json([
             'status'=> FALSE,
             'message' => 'Failed to Delete Symptom.',
         ]);
      }
   }

   public function insertSymptom(Request $request)
   {
      try{
         $checkifexist = Symptoms::where('fldsymptom', $request->symptom)->first();
         if($checkifexist != null){
             return response()->json([
                'status'=> FALSE,
                'message' => 'Symptom Already Exists Search The List.',
            ]);
         }else{
            Symptoms::insert([
               'fldsymptom' => $request->symptom,
               'fldcategory' => $request->category,
               'fldsymdetail' => $request->symdetail
            ]);
            return response()->json([
               'status'=> TRUE,
               'message' => 'Successfully Added Symptom.',
            ]);
         }
      } catch (Exception $e) {
         return response()->json([
             'status'=> FALSE,
             'message' => 'Failed to Add Symptom.',
         ]);
      }
   }

   public function updateSymptom(Request $request)
   {
      try{
         $table = Symptoms::where('fldsymptom', $request->symptomid)->first();
         if($table != null){
            \DB::table('tblsymptoms')->where('fldsymptom', $request->symptomid)->update([
               'fldsymptom' => $request->symptom,
               'fldcategory' => $request->category,
               'fldsymdetail' => $request->detail
            ]);
            return response()->json([
               'status'=> TRUE,
               'message' => __('messages.update', ['name' => 'Data']),
            ]);
         } else {
            return response()->json([
               'status'=> FALSE,
               'message' => 'Symptom Did Not Match.',
            ]);
         }
      } catch (Exception $e) {
         return response()->json([
             'status'=> FALSE,
             'message' => 'Failed to Update Symptom.',
         ]);
      }
   }

   public function getSymptom()
   {
      $returnSymptoms = Symptoms::select('fldsymptom', 'fldcategory', 'fldsymdetail')->get();
      return response()->json($returnSymptoms);
   }
}

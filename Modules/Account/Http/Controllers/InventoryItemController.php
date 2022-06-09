<?php

namespace Modules\Account\Http\Controllers;

use App\BillingSet;
use App\Drug;
use App\MedicineBrand;
use App\StockRate;
use App\Surgical;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Cache;

class InventoryItemController extends Controller
{
    public function index()
    {
        $itemcategory = 'Other Items';
        $routes = ['oral', 'liquid', 'fluid', 'injextion', 'resp', 'topical', 'eye/ear', 'anal/vaginal', 'suture', 'msurg', 'ortho', 'extra'];
        $billingset = BillingSet::get();
        return view('account::inventoryItem', compact('billingset', 'routes'));
    }

    public function getItems(Request $request)
    {
        // select flditemname,fldbillingmode,flddrug,fldstockid,fldrate from tblstockrate where fldbillingmode='General'
        if($request->search_stockrate == '' || $request->search_stockrate == 'undefined' ){
            $search = '';
        }else{
            $search = $request->search_stockrate;
        }


        $result = \DB::table('tblstockrate')
                ->where('fldbillingmode',$request->get('fldbillingmode'))
                ->where('flditemname','like','%'.$search.'%')
                ->groupby('flditemname')
                ->paginate(15);

   

        // return response()->json(
        //     \App\StockRate::select('fldid', 'flditemname', 'fldbillingmode', 'flddrug', 'fldstockid', 'fldrate', 'fldcategory')->where('fldbillingmode', $request->get('fldbillingmode'))->get()
        // );

        return view('account::stockrateitem',compact('result'))->render();
    }


    public function getMedicineItem(Request $request)
    {
        if($request->search_meds == '' || $request->search_meds == 'undefined' ){
            $search = '';
        }else{
            $search = $request->search_meds;
        }

        
        // select flditemname,fldbillingmode,flddrug,fldstockid,fldrate from tblstockrate where fldbillingmode='General'

        //select tblentry.fldstockid as col,tblentry.fldexpiry as expiry,tblentry.fldqty as qty,tblentry.fldsellpr as sellpr, 
        //tblentry.fldstatus as status,tblentry.fldbatch as batch,tbldrug.fldroute as route,tblmedbrand.fldbrand,fldmedcodeno as code 
        //from tblentry join tblmedbrand on tblentry.fldstockid=tblmedbrand.fldbrandid 
        //join tbldrug on tblmedbrand.flddrug=tbldrug.flddrug where tblentry.fldcomp=&1 and tblentry.fldqty>&2 and 
        //tblentry.fldstatus>&6 and tblentry.fldstockid in(select tblmedbrand.fldbrandid From tblmedbrand where 
        //tblmedbrand.fldactive=&3 and tblmedbrand.flddrug in(select tbldrug.flddrug From tbldrug where tbldrug.fldroute=&4)) 
        //and tblentry.fldexpiry>=&5 ORDER BY tblentry.fldstockid ASC

        //sql = "select tblentry.fldstatus as status,tblentry.fldstockid as col,tblentry.fldexpiry as expiry,tblentry.fldqty as 
        //qty,tblentry.fldsellpr as sellpr,tblentry.fldbatch as batch,tblsurgicals.fldsurgcateg as route,tblsurgbrand.fldbrand,
        //fldsurgcodeno as code from tblentry join tblsurgbrand on tblentry.fldstockid=tblsurgbrand.fldbrandid join tblsurgicals on 
        //tblsurgbrand.fldsurgid=tblsurgicals.fldsurgid where tblentry.fldcomp=&1 and tblentry.fldstatus>&6 and tblentry.fldqty>&2 
        //and tblentry.fldstockid in(Select tblsurgbrand.fldbrandid From tblsurgbrand where tblsurgbrand.fldactive=&3 and 
        //tblsurgbrand.fldsurgid in(select tblsurgicals.fldsurgid from tblsurgicals where tblsurgicals.fldsurgcateg=&4)) 
        //and tblentry.fldexpiry>= &5 ORDER BY tblentry.fldstockid ASC"


        if($request->medtype == 'Medicines'){
            $result = \DB::table('tblentry as et')
                ->join('tblmedbrand as mb','et.fldstockid','mb.fldbrandid')
                ->join('tbldrug as d','mb.flddrug','d.flddrug')
                // ->where('et.fldcomp','comp01')
                // ->where('et.fldqty','>',0)
                // ->where('et.fldstatus','>',-1)
                ->where('mb.fldactive','Active')
                ->where('d.flddrug','like','%'.$search.'%')
                ->groupBy('d.flddrug')
                ->orderBy('et.fldstockid','ASC')
                ->paginate(15);

        }else if($request->medtype == 'Surgicals'){
            $result = \DB::table('tblentry as et')
                ->join('tblsurgbrand as mb','et.fldstockid','mb.fldbrandid')
                ->join('tblsurgicals as d','mb.fldsurgid','d.fldsurgid')
                // ->where('et.fldcomp','comp01')
                // ->where('et.fldqty','>',0)
                // ->where('et.fldstatus','>',-1)
                ->where('mb.fldactive','Active')
                ->where('d.fldsurgid','like','%'.$search.'%')
                ->selectRaw('d.fldsurgid as flddrug')
                ->groupBy('d.fldsurgid')
                ->orderBy('et.fldstockid','ASC')
                ->paginate(15);

                
            

        }else{

            $result = \DB::table('tblentry as et')
            ->join('tblextrabrand as mb','et.fldstockid','mb.fldbrandid')
            // ->where('et.fldcomp','comp01')
            // ->where('et.fldqty','>',0)
            // ->where('et.fldstatus','>',-1)
            ->where('mb.fldactive','Active')
            ->where('mb.fldextraid','like','%'.$search.'%')
            ->selectRaw('mb.fldextraid as flddrug')
            ->groupBy('mb.fldextraid')
            ->orderBy('et.fldstockid','ASC')
            ->paginate(15);

        }

        $medtype = $request->medtype;

       
        

        // return response()->json(
        //     \App\StockRate::select('fldid', 'flditemname', 'fldbillingmode', 'flddrug', 'fldstockid', 'fldrate', 'fldcategory')->where('fldbillingmode', $request->get('fldbillingmode'))->get()
        // );

        return view('account::medicineaccordion',compact('result','medtype'))->render();
    }

    public function getMedicinesOld(Request $request)
    {
        // select tblentry.fldstockid as col,tblentry.fldqty as qty from tblentry where lower(tblentry.fldstockid) like '%' and tblentry.fldstatus=1 and tblentry.fldcomp='comp07' and tblentry.fldqty>0 and tblentry.fldstockid in(select tblmedbrand.fldbrandid From tblmedbrand where tblmedbrand.fldactive='Active' and tblmedbrand.flddrug in(select tbldrug.flddrug From tbldrug where tbldrug.fldroute='oral')) ORDER BY tblentry.fldstockid ASC
        return response()->json(
            \DB::select("
                SELECT tblentry.fldstockid AS col,tblentry.fldqty AS qty,tblentry.fldcategory
                FROM tblentry
                WHERE lower(tblentry.fldstockid) LIKE '%' AND
                    tblentry.fldstatus=? AND
                    tblentry.hospital_department_id=? AND
                    tblentry.fldqty>0 AND
                    tblentry.fldstockid IN(
                        SELECT tblmedbrand.fldbrandid
                        FROM tblmedbrand
                        WHERE tblmedbrand.fldactive=? AND
                        tblmedbrand.flddrug IN(
                            SELECT tbldrug.flddrug
                            FROM tbldrug
                            WHERE tbldrug.fldroute=?
                        )
                    )
                ORDER BY tblentry.fldstockid ASC",
                [
                    1,
                    Helpers::getUserSelectedHospitalDepartmentIdSession(),
                    'Active',
                    $request->get('fldroute')
                ])
        );
    }

    public function getMedicines(Request $request)
    {
        $drug = $request->drug;
        /*$data['generic_brand'] = $request->generic_brand;*/
        $inStock = 'yes';
        $data['medicineType'] = 'all';
        if ($drug == 'msurg' || $drug == 'ortho') {
            $data['medicineType'] = 'msurg-ortho';
            $data['newOrderData'] = Surgical::select('fldsurgid')
            ->where('fldsurgcateg', $drug)
            ->orderby('fldsurgid', 'ASC')
            ->get();
            $html = view('store::stocktransfer.stock-send', $data)->render();
        } else {
            $flddrug = Drug::where('fldroute', $drug)->pluck('flddrug');

            $data['newOrderData'] = MedicineBrand::select('fldbrand', 'fldbrandid', 'flddrug', 'flddosageform')
            ->whereRaw('lower(fldbrand) like ?', array('%'))
                ->where('fldmaxqty', '<>', '-1')
                ->where('fldactive', 'Active')
                ->whereIn('flddrug', $flddrug)
                ->whereHas('entry', function ($query) use ($inStock) {
                    if ($inStock == 'yes') {
                        return $query->havingRaw('SUM(fldqty) > 0');
                    } else {
                        return $query->havingRaw('SUM(fldqty) = 0');
                    }
                })
                ->orderby('fldbrand', 'ASC')
                ->with(['Drug', 'entry'])
                ->get();

            $html = view('store::stocktransfer.stock-send', $data)->render();
        }

        return $html;
    }

    public function getBrandName(Request $request)
    {
        // select fldbrand from tblmedbrand where fldbrandid='Acyclovir- 800 mg (ACIVIR 800 DT)'
        return response()->json(
            \App\MedicineBrand::select('fldbrand')->where('fldbrandid', $request->get('fldbrandid'))->first()
        );
    }

    public function saveUpdate(Request $request)
    {

        try {
            $fldid = $request->get('fldid');
            $data = [
                'flditemname' => $request->get('flditemname') ?? '',
                'fldbillingmode' => strtolower($request->get('fldbillingmode')) ?? '',
                'fldcategory' => $request->get('fldcategory') ?? '',
                'flddrug' => $request->get('flddrug') ?? '',
                'fldstockid' => $request->get('fldstockid') ?? '',
                'fldrate' => $request->get('fldrate') ?? '',
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];
            $message = '';
            if ($fldid) {
                $stock_rate = \App\StockRate::where([
                    'fldid' => $fldid
                ])->first();

                // for update function insert as raw array for category.
                $stock_rate->update($data);
                $message = 'Updated Successfully';
                Helpers::logStack(["Stock rate updated", "Event"], ['current_data' => $data, 'previous_data' => $stock_rate]);
            } else {
                $time = date('Y-m-d H:i:s');
                $userid = \App\Utils\Helpers::getCurrentUserName();
                $computer = \App\Utils\Helpers::getCompName();

                $fldid = \App\StockRate::insertGetId([
                    'flduserid' => $userid,
                    'fldtime' => $time,
                    'fldcomp' => $computer,
                    'fldsave' => '1',
                    'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                ] + $data);

                Helpers::logStack(["Stock rate created", "Event"], ['current_data' => [
                    'fldid' => $fldid,
                    'flduserid' => $userid,
                    'fldtime' => $time,
                    'fldcomp' => $computer,
                    'flditemtype' => $this->_itemcategory,
                ] + $data]);
                $message = 'Created Successfully';
            }
            return response()->json([
                'status' => TRUE,
                'data' => $data + ['fldid' => $fldid],
                'message' =>$message,
            ]);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in stock rate create/update', "Error"]);
            return response()->json([
                'status' => FALSE,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function delete(Request $request)
    {
        try {
            $data = \App\StockRate::where('fldid', '=', $request->get('fldid'))->delete();
            Helpers::logStack(["Stock rate deleted", "Event"], ['previous_data' => $data]);
            return response()->json([
                'status' => TRUE,
                'message' => __('messages.success', ['name' => 'Information']),
            ]);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in stock rate delete', "Error"]);
            return response()->json([
                'status' => FALSE,
                'message' => $e->getMessage(),
            ]);
        }
    }


    public function savestockrateitem(Request $request){

        try{

            $stocknamearr = $request->stocknamearr;
            $mednamearr = $request->medbrandarr;
            $billingmode = $request->billingmode;
            $itemtype = $request->itemtype;

            foreach($mednamearr as $mednamearrinv){


                foreach($stocknamearr as $stocknamearrinv){

                // $result = \App\StockRate::where('flditemname',$stocknamearrinv)
                //     ->where('fldstockid',$mednamearrinv)
                //     ->get();

                $result = \App\StockRate::where('fldstockid',$mednamearrinv)
                ->get();

                    

                if($result->isNotEmpty()){

                    // return response()->json(["message" => "Data Already Inserted!", "status" => false]);
                   

                }else{

                    $stockrate = \App\StockRate::where('flditemname',$stocknamearrinv)->pluck('fldrate');

                    if($stockrate->isNotEmpty()){

                        \DB::beginTransaction();

                        // $insert = \App\StockRate::updateOrCreate(
                        //     ['flditemname' => $stocknamearrinv, 'fldbillingmode' => $billingmode, 'fldstockid'=> ''] ,
                        //     [   'fldcategory' => $itemtype,
                        //         'flddrug' => $mednamearrinv,
                        //         'flduserid' => Helpers::getCurrentUserName(),
                        //         'fldcomp' => Helpers::getCompName(),
                        //         'fldstockid' => $mednamearrinv,
                        //         'fldtime' => \Carbon\Carbon::now(),
                        //         'fldrate' => $stockrate[0],
                        //         'fldsave' => '1'
    
                        //     ]);
    
                        $insert = \App\StockRate::insert([
                            'flditemname' => $stocknamearrinv,
                            'fldbillingmode' => $billingmode,
                            'fldcategory' => $itemtype,
                            'flddrug' => $mednamearrinv,
                            'fldstockid' => $mednamearrinv,
                            'fldrate' => $stockrate[0],
                            'flduserid' => Helpers::getCurrentUserName(),
                            'fldtime' => \Carbon\Carbon::now(),
                            'fldcomp' => Helpers::getCompName(),
                            'fldsave' => '1'
    
    
                        ]);
            
                        \DB::commit();
    

                    }

                   
                }
            }

            }
            

            
            

            if(isset($insert)){
                return response()->json(["message" => "Inserted Successfully!","status" => true]);
            }else{
                return response()->json(["message" => "Item Already Inserted!", "status" => false]);
            }
            

        }catch(\Exception $e){

            // DB::Rollback();
            dd($e);

        }

    }


    public function deletestockrateitem(Request $request){

        try{

            $stocknamearr = $request->stocknamearr;
            $mednamearr = $request->medbrandarr;
            $stocknamebrand = $request->stocknamebrand;
            $billingmode = $request->billingmode;
            $itemtype = $request->itemtype;

            foreach($stocknamebrand as $mednamearrinv){


                foreach($stocknamearr as $stocknamearrinv){

                $result = \App\PatBilling::where('hiitemname',$stocknamearrinv)
                    ->where('flditemname',$mednamearrinv)
                    ->get();

                    

                if($result->isNotEmpty()){

                    // return response()->json(["message" => "Item already Billed! Cannot be changed!", "status" => false]);
                    

                }else{

                    \DB::beginTransaction();

                    $delete = \App\StockRate::where('flditemname',$stocknamearrinv)->where('fldstockid',$mednamearrinv)->delete();
        
                    \DB::commit();

                }
            }

            }
            

            
            

            if(isset($delete)){
                return response()->json(["message" => "Item Removed Successfully!","status" => true]);
            }else{
                return response()->json(["message" => "Item already Billed! Cannot be changed!", "status" => false]);
            }
            

        }catch(\Exception $e){

            // DB::Rollback();
            dd($e);

        }

    }

    public function exportstockrateitem(Request $request){

        $billingmode = $request->billingmode;
        $value = $request->value;
        $userid = Helpers::getCurrentUserName();

        if($value == "mapitem"){

            $result = \App\StockRate::where('fldbillingmode',$billingmode)
                ->whereRaw('fldstockid is not Null')
                ->whereRaw('flddrug is Not Null')
                ->orderBy('flditemname')
                ->get();

            if($result->isNotEmpty()){

                return view('account::stockrateitemexport', compact('result','userid','billingmode'));

            }else{
    
                $message = "No Items Found";

                return view('account::stockrateitemexport', compact('message','userid','billingmode'));

            }

        }else{

            $result = \App\StockRate::where('fldbillingmode',$billingmode)
                ->orderBy('flditemname')
                ->get();

            if($result->isNotEmpty()){

                return view('account::stockrateitemexport', compact('result','userid','billingmode'));

            }else{

                $message = "No Items Found";

                return view('account::stockrateitemexport', compact('message','userid','billingmode'));

            }

        }

        

    }

}

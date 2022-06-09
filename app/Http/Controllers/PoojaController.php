<?php

namespace App\Http\Controllers;

use App\Drug;
use App\Drugs;
use App\Entry;
use App\ExtraBrand;
use App\HospitalDepartment;
use App\MedicineBrand;
use App\Purchase;
use App\SurgBrand;
use App\Surgical;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use \DB;

class PoojaController extends Controller
{

    public function create_units()
    {
        $medunits = MedicineBrand::select('flddosageform', 'fldvolunit')->groupBy('fldvolunit')->get();
        if ($medunits) {
            foreach ($medunits as $medunit) {
                $productUnitName = $medunit->flddosageform;
                $productUnitShortName = $medunit->fldvolunit;
                try {
                    $client =  new Client();
                    $url = "http://103.65.201.210:5080/ELekha-web/elekha/elekhaApi/createProductMeasurementUnit";
                    $response = $client->request('POST', $url, [
                        'json' => [
                            'officeId' => '184',
                            'userId' => '354',
                            'refOrganizationId' => '1',
                            'refOrganizationUserId' => '1',
                            'signature' => '35411184',
                            'productUnitName' => $productUnitName,
                            'productUnitShortName' => $productUnitShortName,
                            'createdBy' => '1',
                            'refId' => '123',
                            'apiKey' => '#eLekh@!123'
                        ],
                        'headers' => [
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json',
                        ],
                        'auth' => ['admin', 'admin']

                    ]);
                    //dd($response);

                    echo $response->getBody();
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        }

        // $surunits = SurgBrand::select('flddosageform','fldvolunit')->groupBy('fldvolunit')->get();
        // if ($surunits) {
        //     foreach ($surunits as $surunit) {
        //         $productUnitName = $surunit->flddosageform;
        //         $productUnitShortName = $surunit->fldvolunit;
        //         try {
        //             $client =  new Client();
        //             $url = "http://103.65.201.210:5080/ELekha-web/elekha/misResources/createProductMeasurementUnit";
        //             $response = $client->request('POST', $url, [
        //                 'json' => [
        //                     'officeId' => '184',
        //                     'userId' => '354',
        //                     'refOrganizationId' => '1',
        //                     'refOrganizationUserId' => '1',
        //                     'signature' => '35411184',
        //                     "productUnitName" => $productUnitName,
        //                     "productUnitShortName" => $productUnitShortName,
        //                     "createdBy" => "Pooja",
        //                     "refId" => "123",
        //                     "apiKey" => "#eLekh@!123"
        //                 ],
        //                 'headers' => [
        //                     'Accept' => 'application/json',
        //                     'Content-Type' => 'application/json',
        //                 ],
        //                 'auth' => ['admin', 'admin']

        //             ]);

        //             echo $response->getBody();
        //         } catch (Exception $e) {
        //             echo $e->getMessage();
        //         }
        //     }
        // }

        // $medunits = MedicineBrand::select('flddosageform','fldvolunit')->groupBy('fldvolunit')->get();
        // if ($medunits) {
        //     foreach ($medunits as $medunit) {
        //         $productUnitName = $medunit->flddosageform;
        //         $productUnitShortName = $medunit->fldvolunit;
        //         try {
        //             $client =  new Client();
        //             $url = "http://103.65.201.210:5080/ELekha-web/elekha/misResources/createProductMeasurementUnit";
        //             $response = $client->request('POST', $url, [
        //                 'json' => [
        //                     'officeId' => '184',
        //                     'userId' => '354',
        //                     'refOrganizationId' => '1',
        //                     'refOrganizationUserId' => '1',
        //                     'signature' => '35411184',
        //                     "productUnitName" => $productUnitName,
        //                     "productUnitShortName" => $productUnitShortName,
        //                     "createdBy" => "Pooja",
        //                     "refId" => "123",
        //                     "apiKey" => "#eLekh@!123"
        //                 ],
        //                 'headers' => [
        //                     'Accept' => 'application/json',
        //                     'Content-Type' => 'application/json',
        //                 ],
        //                 'auth' => ['admin', 'admin']

        //             ]);

        //             echo $response->getBody();
        //         } catch (Exception $e) {
        //             echo $e->getMessage();
        //         }
        //     }
        // }


    }

    public function category()
    {
        $category = [
            'Medicines',
            'Surgicals',
            'Extra'
        ];

        if ($category) {
            foreach ($category as $k => $cat) {
                $categoryRefId = $k;
                $itemCategoryName = $cat;
                $itemCode = $cat;
                try {
                    $client =  new Client();
                    $url = "http://103.65.201.210:5080/ELekha-web/elekha/misResources/createItemCategory";
                    $response = $client->request('POST', $url, [
                        'json' => [
                            'officeId' => '184',
                            'userId' => '354',
                            'refOrganizationId' => '1',
                            'refOrganizationUserId' => '1',
                            'signature' => '35411184',
                            'itemCategoryName' =>  $itemCategoryName,
                            'itemCode' => $itemCode,
                            'categoryRefId' =>  $categoryRefId,
                            'createdBy' => 'Pooja',
                            'apiKey' => '#eLekh@!123',
                        ],
                        'headers' => [
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json',
                        ],
                        'auth' => ['admin', 'admin']

                    ]);

                    echo $response->getBody();
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        }
    }

    public function subcategorymedicine()
    {
        $drugs = MedicineBrand::all();
        if ($drugs) {
            foreach ($drugs as $drug) {
                $categoryRefId = $drug->medid;
                $itemCategoryName = $drug->flddrug;
                $categoryName = 'Medicines';
                try {
                    $client =  new Client();
                    $url = "http://103.65.201.210:5080/ELekha-web/elekha/misResources/createItemSubCategory";
                    $response = $client->request('POST', $url, [
                        'json' => [
                            'officeId' => '184',
                            'userId' => '354',
                            'refOrganizationId' => '1',
                            'refOrganizationUserId' => '1',
                            'signature' => '35411184',
                            'itemSubCategoryName' =>  $itemCategoryName,
                            'itemCategoryName' => $categoryName,
                            'categoryRefId' =>  $categoryRefId,
                            'createdBy' => 'Pooja',
                            'apiKey' => '#eLekh@!123',
                        ],
                        'headers' => [
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json',
                        ],
                        'auth' => ['admin', 'admin']

                    ]);

                    echo $response->getBody();
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        }
    }

    public function subcategorysurgical()
    {
        $drugs = SurgBrand::all();
        if ($drugs) {
            foreach ($drugs as $drug) {
                $categoryRefId = $drug->fldsurgbrand_id;
                $itemCategoryName = $drug->fldsurgid;
                $categoryName = 'Surgicals';
                try {
                    $client =  new Client();
                    $url = "http://103.65.201.210:5080/ELekha-web/elekha/misResources/createItemSubCategory";
                    $response = $client->request('POST', $url, [
                        'json' => [
                            'officeId' => '184',
                            'userId' => '354',
                            'refOrganizationId' => '1',
                            'refOrganizationUserId' => '1',
                            'signature' => '35411184',
                            'itemSubCategoryName' =>  $itemCategoryName,
                            'itemCategoryName' => $categoryName,
                            'categoryRefId' =>  $categoryRefId,
                            'createdBy' => 'Pooja',
                            'apiKey' => '#eLekh@!123',
                        ],
                        'headers' => [
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json',
                        ],
                        'auth' => ['admin', 'admin']

                    ]);

                    echo $response->getBody();
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        }
    }

    public function subcategoryextra()
    {
        $drugs = ExtraBrand::all();
        if ($drugs) {
            foreach ($drugs as $drug) {
                $categoryRefId = $drug->fldextrabrand_id;
                $itemCategoryName = $drug->fldextraid;
                $categoryName = 'Extra';
                try {
                    $client =  new Client();
                    $url = "http://103.65.201.210:5080/ELekha-web/elekha/misResources/createItemSubCategory";
                    $response = $client->request('POST', $url, [
                        'json' => [
                            'officeId' => '184',
                            'userId' => '354',
                            'refOrganizationId' => '1',
                            'refOrganizationUserId' => '1',
                            'signature' => '35411184',
                            'itemSubCategoryName' =>  $itemCategoryName,
                            'itemCategoryName' => $categoryName,
                            'categoryRefId' =>  $categoryRefId,
                            'createdBy' => 'Pooja',
                            'apiKey' => '#eLekh@!123',
                        ],
                        'headers' => [
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json',
                        ],
                        'auth' => ['admin', 'admin']

                    ]);

                    echo $response->getBody();
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        }
    }

    function get_subcategory_id($subcategory)
    {
        $drugs = SurgBrand::all();
        if ($drugs) {
            foreach ($drugs as $drug) {
                $categoryRefId = $drug->fldsurgbrand_id;
                $itemCategoryName = $drug->fldsurgid;
                $itemCategoryId = 2;
                try {
                    $client =  new Client();
                    $url = "http://103.65.201.210:5080/ELekha-web/elekha/misResources/returnCategoryByRefId/184/1/1651";
                    $response = $client->request('GET', $url, [
                        'headers' => [
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json',
                        ],
                        'auth' => ['admin', 'admin']

                    ]);

                    $subcategory =  $response->getBody();
                    return $subcategory->itemCategoryId;
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        }
    }


    function item_create()
    {
        $entry = MedicineBrand::all();
        if ($entry) {
            foreach ($entry as $en) {

                $itemCode = $en->medid;
                $itemName = $en->fldbrandid;
                $itemRefId = $en->medid;
                $category = $en->fldcategory;
                $itemSubCatName = $en->flddrug;
                $vatable = false;
                $unit = $en->fldvolunit;
                // if($category == 'Medicines'){
                //     $medsub = MedicineBrand::where('fldbrandid',$itemName)->get()->first();
                //     $itemSubCatName = $medsub->flddrug;
                // }

                // if($category == 'Surgicals'){
                //     $medsub = SurgBrand::where('fldsurgid',$itemName)->get()->first();
                //     $itemSubCatName = $medsub->fldsurgid;
                // }

                // if($category == 'Extra'){
                //     $medsub = ExtraBrand::where('fldextraid',$itemName)->get()->first();
                //     $itemSubCatName = $medsub->fldextraid;
                // }

                $tt = [
                    'officeId' => '184',
                    'userId' => '354',
                    'refOrganizationId' => '1',
                    'refOrganizationUserId' => '1',
                    'signature' => '35411184',
                    'itemRefId' => $itemRefId,
                    'itemCode' =>  $itemCode,
                    'itemName' => $itemName,
                    'itemSubCatName' => $itemSubCatName,
                    "itemSubCat" => "",
                    'vatable' => $vatable,
                    'serviceType' => true,
                    'transactionRemarks' => '',
                    'isCapitalItem' => false,
                    'createAccount' => true,
                    "mainUnit" => $unit,
                    "createdBy" => 'Pooja',
                    "apiKey" => '#eLekh@!123'
                ];

                dd($tt);


                try {
                    $client =  new Client();
                    $url = "http://103.65.201.210:5080/ELekha-web/elekha/misResources/createItem";
                    $response = $client->request('POST', $url, [
                        'json' => [
                            'officeId' => '184',
                            'userId' => '354',
                            'refOrganizationId' => '1',
                            'refOrganizationUserId' => '1',
                            'signature' => '35411184',
                            'itemRefId' => $itemRefId,
                            'itemCode' =>  $itemCode,
                            'itemName' => $itemName,
                            'itemSubCatName' => $itemSubCatName,
                            "itemSubCat" => "",
                            'vatable' => false,
                            'serviceType' => true,
                            'transactionRemarks' => '',
                            'isCapitalItem' => false,
                            'createAccount' => true,
                            "createdBy" => 'Pooja',
                            "apiKey" => '#eLekh@!123'


                        ],
                        'headers' => [
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json',
                        ],
                        'auth' => ['admin', 'admin']

                    ]);

                    echo $response->getBody();
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        }
    }

    function create_warehouse()
    {
        $wareh = DB::table('hospital_departments')
                ->join('hospital_branches', 'hospital_branches.id', '=', 'hospital_departments.branch_id')
                ->select('hospital_departments.id as departmentid','hospital_departments.name as departmentname','hospital_branches.name as branchname','hospital_branches.address as branchaddress')
                ->get();
        if($wareh){
            foreach($wareh as $house){
                $refId = $house->departmentid;
                $wareHouseName = $house->departmentname.'('.$house->branchname.')';
                $wareHouseLocation = $house->branchaddress;

                try {
                    $client =  new Client();
                    $url = "http://103.65.201.210:5080/ELekha-web/elekha/elekhaApi/createWarehouse";
                    $response = $client->request('POST', $url, [
                        'json' => [
                            'officeId' => '184',
                            'userId' => '354',
                            'refOrganizationId' => '1',
                            'refOrganizationUserId' => '1',
                            'signature' => '35411184',
                            'wareHouseName' => $wareHouseName,
                            'wareHouseLocation' =>  $wareHouseLocation,
                            "refId" => $refId,
                            "createdBy" => 'Pooja',
                            "apiKey" => '#eLekh@!123'


                        ],
                        'headers' => [
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json',
                        ],
                        'auth' => ['admin', 'admin']

                    ]);

                    echo $response->getBody();
                } catch (Exception $e) {
                    echo $e->getMessage();
                }


            }
        }


    }

    function list_warehouse()
    {
        $officeId = 184;

        try {
            $client =  new Client();
            $url = "http://103.65.201.210:5080/ELekha-web/elekha/elekhaApi/warehouseList/" . $officeId;
            $response = $client->request('GET', $url, [

                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'auth' => ['admin', 'admin']

            ]);

            echo $response->getBody();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    function list_unit()
    {
        $officeId = 184;

        try {
            $client =  new Client();
            $url = "http://103.65.201.210:5080/ELekha-web/elekha/elekhaApi/measurementUnit/" . $officeId;
            $response = $client->request('GET', $url, [

                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'auth' => ['admin', 'admin']

            ]);

            echo $response->getBody();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    function sales_invoice()
    {

pursehare
        $purchase = Purchase::get();
group by
        if($purchase){
            foreach($purchase as $pur){
                $totalBillAmount = total garna parcha $pur->fldtotalcost;
                $taxableAmount = 0;
                $subTotalAmount = total garna parcha $pur->fldtotalcost;
                $vatPercentage = 0;
                $vatAmount = 0;
                $transactionRemarks = 25;
                $accountRefId = $pur->fldid;
                $officeAccountId = 25; //???
                $billNo = $pur->fldbillno;
                $transactionDate = $pur->fldtime;
            }
        }




        try {
            $salesArray = [
                'itemId' => 'fldstockid',
                'rate' => 'fldsellprice',
                'quantity' => 'fldtotalqty'
            ];
            $client =  new Client();
            $url = "http://103.65.201.210:5080/ELekha-web/elekha/sales/saveSalesInvoice";
            $response = $client->request('POST', $url, [
                'json' => [
                    'officeId' => '184',
                    'userId' => '354',
                    'refOrganizationId' => '1',
                    'refOrganizationUserId' => '1',
                    'signature' => '35411184',
                    "createdBy" => 'Pooja',
                    "apiKey" => '#eLekh@!123',
                    'totalBillAmount' => $totalBillAmount,
                    'taxableAmount' =>  $taxableAmount,
                    "subTotalAmount" => $subTotalAmount,
                    "vatPercentage" => $vatPercentage,
                    "vatAmount" => $vatAmount,
                    "transactionRemarks" => $transactionRemarks,
                    "accountRefId" => $accountRefId,
                    "officeAccountId" => $officeAccountId,
                    "billNo" => $billNo,
                    "transactionDate" => $transactionDate,
                    'salesList' => $salesArray




                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'auth' => ['admin', 'admin']

            ]);

            echo $response->getBody();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    function purchase_invoice()
    {

        patbillidetail bata aaucha
        patbilling
        $refId = ;
        $totalBillAmount = 25;
        $taxableAmount = 25;
        $nonTaxableAmount = 25;
        $subTotalAmount = 25;
        $vatPercentage = 25;
        $vatAmount = 25;
        $discount = 25;
        $billNo = 25;
        $transactionDate = 25;
        $accountRefId= 25;
        $officeAccountId= 25;
        $warehouseId= 149;

        try {
            $salesArray = [
                'itemId' => '',
                'rate' => '',
                'quantity' => ''
            ];
            $client =  new Client();
            $url = "http://103.65.201.210:5080/ELekha-web/elekha//sales/createPurchaseInvoice";
            $response = $client->request('POST', $url, [
                'json' => [
                    'officeId' => '184',
                    'userId' => '354',
                    'refOrganizationId' => '1',
                    'refOrganizationUserId' => '1',
                    'signature' => '35411184',
                    "createdBy" => 'Pooja',
                    "apiKey" => '#eLekh@!123',
                    "refId" =>$refId ,
                    "totalBillAmount" =>$totalBillAmount ,
                    "taxableAmount" =>$taxableAmount,
                    "nonTaxableAmount" =>$nonTaxableAmount ,
                    "subTotalAmount" => $subTotalAmount ,
                    "vatPercentage" =>$vatPercentage ,
                    "vatAmount" =>$vatAmount ,
                    "discount" =>$discount,
                    "billNo" =>$billNo ,
                    "transactionDate" =>$transactionDate ,
                    "accountRefId" =>$accountRefId,
                    "officeAccountId" =>$officeAccountId,
                    "warehouseId" =>$warehouseId,
                    'salesList' => $salesArray




                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'auth' => ['admin', 'admin']

            ]);

            echo $response->getBody();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

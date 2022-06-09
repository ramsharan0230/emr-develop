<?php


namespace App\Utils;


use App\Supplier;

class Inventoryhelpers
{

    public static function getAllActiveSuppliers() {

        $suppliers = Supplier::where('fldactive', 'Active')->get();

        return $suppliers;
    }

    public static function getSuppliersAddressFromSuppliersName($suppliername) {

        $supplier = Supplier::where('fldsuppname', $suppliername)->first();

        $supplieraddress = '';
        if($supplier) {
            $supplieraddress = $supplier->fldsuppaddress;
        }

        return $supplieraddress;
    }
}

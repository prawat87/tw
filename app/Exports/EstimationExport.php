<?php

namespace App\Exports;
use App\Models\Projects;
use App\Models\Estimation;
use App\Models\Utility;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EstimationExport implements FromCollection,WithHeadings
{
   
        public function collection()
    {
        $data = Estimation::get();

        foreach($data as $k => $Estimation)
        {
            unset($Estimation->created_by);
               $client_name                  = Projects::taxRate($Estimation->client_id);
               $tax_name                     = Projects::tax_nm($Estimation->tax_id);
                                      
            
            $data[$k]["estimation_id"]           = Utility::estimateNumberFormat($Estimation->estimation_id) ;
            $data[$k]["client_id"]               =  $client_name;
            $data[$k]["status"]                   = Estimation::$statues[$Estimation->status];
            $data[$k]["issue_date"]               = $Estimation->issue_date;
            $data[$k]["discount"]                 = $Estimation->discount;
            $data[$k]["tax_id"]                   =  $tax_name ;
            $data[$k]["terms"]                 = $Estimation->terms;
         
        }  

        return $data;
    }

    public function headings(): array
    {
        return [
            "ID",
            "estimation_id",
            "client_id",
            "status",
            "issue_date",
            "discount",
            "tax_id",
            "terms",
            "Created At",
            "Updated At",
        ];
    }
}

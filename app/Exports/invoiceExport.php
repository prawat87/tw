<?php

namespace App\Exports;

use App\Models\Invoice;
use App\Models\Utility;
use App\Models\Projects;
use App\Models\Timesheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class invoiceExport implements FromCollection,WithHeadings
{
     public function collection()
    {
        $data = Invoice::get();

        foreach($data as $k => $Invoice)
        {
            unset($Invoice->created_by,$Invoice->terms);

            $invoice_name = Utility::invoiceNumberFormat($Invoice->invoice_id);
             $pro_nm = Timesheet::project_nm($Invoice->project_id);
            
              $tax_name  = Projects::tax_nm($Invoice->tax_id);
            $data[$k]["invoice_id"]              =  $invoice_name;
            $data[$k]["project_id"]                =$pro_nm;
            $data[$k]["status"]                   = Invoice::$statues[$Invoice->status];
            $data[$k]["issue_date"]               = $Invoice->issue_date;
            $data[$k]["due_date"]                 = $Invoice->due_date;
            $data[$k]["discount"]                 = "0.0";
            $data[$k]["tax_id"]                   = $tax_name ;
          
         
        }  

        return $data;
    }

    public function headings(): array
    {
        return [
            "ID",
            "invoice_id",
            "project_id",
            "status",
            "issue_date",
            "due_date",   
           "discount",
            "tax_id",
            "Created At",
            "Updated At",
        ];
    }
}

        
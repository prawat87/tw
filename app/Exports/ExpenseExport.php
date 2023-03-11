<?php

namespace App\Exports;

use App\Models\Expense;
use App\Models\ExpensesCategory;
use App\Models\Projects;
use App\Models\Timesheet;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExpenseExport implements FromCollection,WithHeadings
{
  
    public function collection()
    {
         $data = Expense::get();
      
        foreach($data as $k => $Expense)
        {
            unset($Expense->created_by, $Expense->attachment);
            
             $category_name =   Projects::ExpensesCategoryss($Expense->category_id);
             $project_name =  Timesheet::project_nm($Expense->project);
             $user_name =      Projects::taxRate($Expense->user_id);  

            $data[$k]["category_id"]           = $category_name;
            $data[$k]["description"]          = $Expense->description;
            $data[$k]["amount"]                = $Expense->amount;
            $data[$k]["date"]                 = $Expense->date;
            $data[$k]["project"]              = $project_name;
            $data[$k]["user_id"]            = $user_name ;
           
        }  

        return $data;
    }



    public function headings(): array
    {
        return [
            "ID",
            "category_id",
            "description",
            "amount",
            "date",
            "project_id",
            "user_id",
            "Created At",
            "Updated At",
        ];
    }
    
}


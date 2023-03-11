<?php

namespace App\Exports;

use App\Models\Bug;
use App\Models\Projects;
use App\Models\Timesheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class bugExport implements FromCollection,WithHeadings
{


protected $id;

 function __construct($id) {
        $this->id = $id;
 }


       public function collection()
    {


        $data = Bug::where('project_id',$this->id)->get();

        foreach($data as $k => $Bug)
        {
            unset($Bug->created_by,$Bug->order);



             $bug_name =   \Auth::user()->bugNumberFormat($Bug->bug_id);
             $project_name =Timesheet::project_nm($Bug->project_id);
             $bug_status =  Projects::bug_status($Bug->status);
             $user_name =   Projects::taxRate($Bug->assign_to);
            
            $data[$k]["bug_id"]                   = $bug_name;
            $data[$k]["project_id"]               = $project_name;
            $data[$k]["title"]                    = $Bug->title;
            $data[$k]["priority"]                 = $Bug->priority;
            $data[$k]["start_date"]               = $Bug->start_date;
            $data[$k]["due_date"]                 = $Bug->due_date;
            $data[$k]["description"]              = $Bug->description;
            $data[$k]["status"]                   = $bug_status;
           
            $data[$k]["assign_to"]                = $user_name;
         
        }  

        return $data;
    }

    public function headings(): array
    {
        return [
            "ID",
            "bug_id",
            "project_id",
            "title",
            "priority",
            "start_date",
            "due_date",
            "description",
             "status",
           
            " assign_to",
            "Created At",
            "Updated At",
        ];
    }
}
     
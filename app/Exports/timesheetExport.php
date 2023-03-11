<?php

namespace App\Exports;
use App\Models\Projects;
use App\Models\Timesheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class timesheetExport implements FromCollection,WithHeadings
{
  
       public function collection()
    {
        $data = Timesheet::get();

        foreach($data as $k => $Timesheet)
        {
            unset($Timesheet->created_by, $Timesheet->remark);
             $pro_nm = Timesheet::project_nm($Timesheet->project_id);
              $user_name = Projects::taxRate($Timesheet->user_id);  
                $task_name = Projects::task_nm($Timesheet->task_id);
            $data[$k]["project_id"]       =  $pro_nm;
            $data[$k]["user_id"]          = $user_name;
            $data[$k]["task_id"]          = $task_name;
            $data[$k]["date"]             = $Timesheet->date;
            $data[$k]["hours"]            = $Timesheet->hours;
          
        }  

        return $data;
    }

    public function headings(): array
    {
        return [
            "ID",
            "project_id",
            "user_id",
            "task_id",
            "date",
            "hours",
            "Created At",
            "Updated At",
        ];
    }
}

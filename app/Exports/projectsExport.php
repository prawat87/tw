<?php

namespace App\Exports;

use App\Models\Projects;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class projectsExport implements FromCollection,WithHeadings
{

        public function collection()
    {
        $data = Projects::get();

        foreach($data as $k => $Projects)
        {
            unset($Projects->created_by, $Projects->is_active);

            $client_name                  = Projects::taxRate($Projects->client);
            $label_name                   = Projects::label_nm($Projects->label);
             $lead_name                   = Projects::lead_nm($Projects->lead);
            $data[$k]["name"]             =  $Projects->name;
            $data[$k]["price"]            =  $Projects->price;
            $data[$k]["start_date"]       =  $Projects->start_date;
            $data[$k]["due_date"]         =  $Projects->due_date;
            $data[$k]["client"]           =  $client_name ;
            $data[$k]["label"]            =  $label_name ;
            $data[$k]["description"]      =  $Projects->description;
            $data[$k]["lead"]             =  $lead_name;
        }  

        return $data;
    }

    public function headings(): array
    {
        return [
            "ID",
            "Name",
            "price",
            "start_date",
            "due_date",
            "client",
            "description",
            "label",
            "lead",
            "status",
            "Created At",
            "Updated At",
        ];
    }
    
}

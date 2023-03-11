<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class clientsExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {


         $client = \Auth::user();
         $data = User::where('created_by', '=', $client->creatorId())->where('type', '=', 'client')->get();
       

        foreach($data as $k => $users)
        {
            unset($users->created_by, $users->active_status,  $users->dark_mode, $users->email_verified_at,$users->remember_token,
                $users->plan,$users->requested_plan,
                $users->password,$users->plan_expire_date,$users->avatar,);
            
            $data[$k]["name"]           = $users->name;
            $data[$k]["email"]          = $users->email;
            $data[$k]["type"]           = $users->type;
            $data[$k]["is_active"]      = '1';
            $data[$k]["delete_status"]  = '1';




        }  

        return $data;
    }

        public function headings(): array
    {
        return [
            "ID",
            "Name",
            "email",   
            "messenger_color",
            "type",
            "lang", 
            "delete_status",
            "is_active" ,
            "Created At",
            "Updated At",
        ];
    }


}

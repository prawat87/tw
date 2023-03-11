<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Contracts;
use App\Models\Utility;
use App\Models\ContractsType;
use App\Models\ContractsAttachment;
use App\Models\Projects;
use App\Models\ContractsComment;
use App\Models\ContractsNote;


class ContractsController extends Controller
{
    public function __construct()
    {
        $this->middleware(
            [
                'auth'
            ]
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if(\Auth::user()->can('manage contracts'))
        // {
            if(\Auth::user()->type == "company"){

                $contracts   = Contracts::where('created_by', '=', \Auth::user()->creatorId())->get();
    
                $curr_month  = Contracts::where('created_by', '=', \Auth::user()->creatorId())->whereMonth('start_date', '=', date('m'))->get();
                $curr_week   = Contracts::where('created_by', '=', \Auth::user()->creatorId())->whereBetween(
                    'start_date', [
                                    \Carbon\Carbon::now()->startOfWeek(),
                                    \Carbon\Carbon::now()->endOfWeek(),
                                ]
                )->get();
                $last_30days = Contracts::where('created_by', '=', \Auth::user()->creatorId())->whereDate('start_date', '>', \Carbon\Carbon::now()->subDays(30))->get();
    
                // Contracts Summary
                $cnt_contract                = [];
                $cnt_contract['total']       = \App\Models\Contracts::getContractSummary($contracts);
                $cnt_contract['this_month']  = \App\Models\Contracts::getContractSummary($curr_month);
                $cnt_contract['this_week']   = \App\Models\Contracts::getContractSummary($curr_week);
                $cnt_contract['last_30days'] = \App\Models\Contracts::getContractSummary($last_30days);
    
                return view('contracts.index', compact('contracts','cnt_contract'));
            }
            elseif(\Auth::user()->type == "client"){

                $contracts   = Contracts::where('client_name', '=', \Auth::user()->id)->get();
    
                $curr_month  = Contracts::where('client_name', '=', \Auth::user()->id)->whereMonth('start_date', '=', date('m'))->get();
                $curr_week   = Contracts::where('client_name', '=', \Auth::user()->id)->whereBetween(
                    'start_date', [
                                    \Carbon\Carbon::now()->startOfWeek(),
                                    \Carbon\Carbon::now()->endOfWeek(),
                                ]
                )->get();
                $last_30days = Contracts::where('client_name', '=', \Auth::user()->id)->whereDate('start_date', '>', \Carbon\Carbon::now()->subDays(30))->get();
    
                // Contracts Summary
                $cnt_contract                = [];
                $cnt_contract['total']       = \App\Models\Contracts::getContractSummary($contracts);
                $cnt_contract['this_month']  = \App\Models\Contracts::getContractSummary($curr_month);
                $cnt_contract['this_week']   = \App\Models\Contracts::getContractSummary($curr_week);
                $cnt_contract['last_30days'] = \App\Models\Contracts::getContractSummary($last_30days);
    
                return view('contracts.index', compact('contracts','cnt_contract'));
            }

        // }/
        // else
        // {
            // return redirect()->back()->with('error', __('Permission Denied.'));
        // }/

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(\Auth::user()->can('create contract'))
        {
            $client       = User::where('type', '=', 'company')->get()->pluck('name', 'id');
            $contractType = ContractsType::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $project      = Projects::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name','id');
            $client_name  = User::where('type', '=', 'client')->get()->pluck('name','id');
            $projects     = Projects::where('created_by',\Auth::user()->creatorId())->pluck('name','id');
            
            return view('contracts.create', compact('client','contractType','project','client_name','projects'));
        }
        else
        {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(\Auth::user()->can('create contract'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                'client_name' => 'required|max:20',
                                'subject' => 'required',
                                'value' => 'required',
                                'type' => 'required',
                                'start_date' => 'required',
                                'end_date' => 'required',
                            ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('contracts.index')->with('error', $messages->first());
            }

            $contract              = new Contracts();
            $contract->id          = $this->ContractNumber();
            $contract->client_name = $request->client_name;
            $contract->project_id  = $request->project;
            $contract->subject     = $request->subject;
            $contract->value       = $request->value;
            $contract->type        = $request->type;
            $contract->start_date  = $request->start_date;
            $contract->end_date    = $request->end_date;
            $contract->description = $request->description;
            $contract->created_by  = \Auth::user()->creatorId();
            $contract->save();

            $settings  = \Utility::settings(\Auth::user()->creatorId());
            
            if(isset($settings['contract_notification']) && $settings['contract_notification'] ==1){
                $msg = 'New Invoice '.Auth::user()->contractNumberFormat($this->ContractNumber()).'  created by  '.\Auth::user()->name.'.';
                \Utility::send_slack_msg($msg);
            }
            if(isset($settings['telegram_contract_notification']) && $settings['telegram_contract_notification'] ==1){
                $resp = 'New  Invoice '.Auth::user()->contractNumberFormat($this->ContractNumber()).'  created by  '.\Auth::user()->name.'.';
                \Utility::send_telegram_msg($resp);
            }
            
            return redirect()->route('contracts.index')->with('success', __('Contract successfully created!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contract = Contracts::find($id);

        if($contract->created_by == \Auth::user()->creatorId())
        {
            $client   = $contract->client;

            return view('contracts.show', compact('contract', 'client'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Contracts $contract)
    {
        if(\Auth::user()->can('edit contract'))
        {
            if($contract->created_by == \Auth::user()->creatorId())
            {
                $client       = User::where('type', '=', 'company')->get()->pluck('name', 'id');
                $contractType = ContractsType::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                $project       = Projects::where('created_by', '=', \Auth::user()->creatorId())->where('client',$contract->client)->get()->pluck('title','id');
                $client_name  = User::where('type', '=', 'client')->get()->pluck('name','id');
            
                return view('contracts.edit', compact('contract', 'contractType', 'client', 'project','client_name'));
            }
            else
            {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contracts $contract)
    {
        if(\Auth::user()->can('edit contract'))
        {
            if($contract->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                        'client_name' => 'required|max:20',
                                        'subject' => 'required',
                                        'value' => 'required',
                                        'type' => 'required',
                                        'start_date' => 'required',
                                        'end_date' => 'required',
                                ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('contracts.index')->with('error', $messages->first());
                }

                // $contract              = Contracts::find($id);
                $contract->client_name        = $request->client_name;                
                $contract->project_id  = $request->project;
                $contract->subject     = $request->subject;
                $contract->value       = $request->value;
                $contract->type        = $request->type;
                $contract->start_date  = $request->start_date;
                $contract->end_date    = $request->end_date;
                $contract->description = $request->description;
                $contract->created_by  = \Auth::user()->creatorId();
                $contract->save();

                return redirect()->route('contracts.index')->with('success', __('Contract successfully updated!'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(\Auth::user()->can('delete contract'))
        {
            $contract =Contracts::find($id);
            if($contract->created_by == \Auth::user()->creatorId())
            {

                $attechments = $contract->ContractAttechment()->get()->each;
        
                foreach($attechments->items as $attechment){
                    if (\Storage::exists('contract_attechment/'.$attechment->files)) {
                            unlink('storage/contract_attechment/'.$attechment->files);
                    }
                    $attechment->delete();
                }
        
                $contract->ContractComment()->get()->each->delete();
                $contract->ContractNote()->get()->each->delete();
                $contract->delete();

                return redirect()->route('contracts.index')->with('success', __('Contract successfully deleted!'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    function ContractNumber()
    {
        $latest = Contracts::where('created_by', '=', \Auth::user()->creatorId())->latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->id + 1;
    }


    public function contract_descriptionStore($id, Request $request)
    {
        $contract        = Contracts::find($id);
        $contract->contract_description = $request->contract_description;
        $contract->save();
        return redirect()->back()->with('success', __('description successfully saved.'));
    }


    public function fileUpload($id, Request $request)
    {
        if(\Auth::user()->can('create attachment'))
        {
            $return['is_success'] = true;
            
            $contract     = Contracts::find($id);
            $request->validate(['file' => 'required']);

            $dir = 'contract_attechment/';
            $files = $request->file->getClientOriginalName();
            $path = Utility::upload_file($request,'file',$files,$dir,[]);
            if($path['flag'] == 1){
                $file = $path['url'];
            }
            else{
                return redirect()->back()->with('error', __($path['msg']));
            }

            $file         = ContractsAttachment::create(
                [
                    'contract_id' => $contract->id,
                    'user_id' => \Auth::user()->id,
                    'files' => $files,
                ]
            );
            $return               = [];
            $return['is_success'] = true;
            $return['download']   = route(
                'contracts.file.download', [
                                            $contract->id,
                                            $file->id,
                                        ]
            );
            $return['delete']     = route(
                'contracts.file.delete', [
                                        $contract->id,
                                        $file->id,
                                    ]
            );

            return response()->json($return);
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function fileDownload($id, $file_id)
    {
        $contract = Contracts::find($id);
        if($contract->created_by == \Auth::user()->creatorId())
        {
            $file = ContractsAttachment::find($file_id);
            if($file)
            {
                $file_path = storage_path('contract_attechment/' . $file->files);

                // $files = $file->files;

                return \Response::download(
                    $file_path, $file->files, [
                                    'Content-Length: ' . filesize($file_path),
                                ]
                );
            }
            else
            {
                return redirect()->back()->with('error', __('File is not exist.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
        
    }


    public function fileDelete($file_id, $id)
    {
        // dd($file_id);
        if((\Auth::user()->type == 'company')||(\Auth::user()->type == 'client'))
        {
            $contract = Contracts::find($id);
            $file = ContractsAttachment::find($file_id);
          
            if($file)
            {
                $path = storage_path('contract_attechment/' . $file->files);
                if(file_exists($path))
                {
                    \File::delete($path);
                }
                $file->delete();

                return redirect()->back()->with('success', __('Attachment successfully deleted!'));
            }
            else
            {
                return response()->json(
                    [
                        'is_success' => false,
                        'error' => __('File is not exist.'),
                    ], 200
                );
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function commentStore(Request $request ,$id)
    {
        if(\Auth::user()->can('store comment'))
        {
            $contract              = new ContractsComment();
            $contract->comment     = $request->comment;
            $contract->contract_id = $id;
            $contract->user_id     = \Auth::user()->id;
            $contract->created_by     = \Auth::user()->id;
            $contract->save();


            return redirect()->back()->with('success', __('comments successfully created!') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''))->with('status', 'comments');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function commentDestroy($id)
    {
        $contract = ContractsComment::find($id);
        $contract->delete();

        return redirect()->back()->with('success', __('Comment successfully deleted!'));
    }


    public function noteStore($id, Request $request)
    {
        if(\Auth::user()->can('store note'))
        {
            $contract              = Contracts::find($id);
            $notes                 = new ContractsNote();
            $notes->contract_id    = $contract->id;
            $notes->notes           = $request->note;
            $notes->user_id        = \Auth::user()->id;
            $notes->created_by     = \Auth::user()->creatorId();

        
            $notes->save();
            return redirect()->back()->with('success', __('Note successfully saved.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }


    public function noteDestroy($id)
    {
        $contract = ContractsNote::find($id);
        if($contract->created_by == \Auth::user()->creatorId())
        {
            $contract->delete();

            return redirect()->back()->with('success', __('Note successfully deleted!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function clientByProject($id)
    {
        $projects = Projects::where('client',$id)->get();
        
        $users=[];
        foreach($projects as $key => $value )
        {
            $users[]=[
                'id' => $value->id,
                'name' => $value->name,
            ];
        }
        // dd($projects);
        return \Response::json($users);
    }


    public function copycontract( $id)
    {
        $contract = Contracts::find($id);
        if($contract->created_by == \Auth::user()->creatorId())
        {
            $client       = User::where('type', '=', 'company')->get()->pluck('name', 'id');
            $contractType = ContractsType::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $project      = Projects::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name','id');
            $client_name  = User::where('type', '=', 'client')->get()->pluck('name','id');

            return view('contracts.copy', compact('contract', 'contractType', 'client', 'project','client_name'));
        }
        else
        {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }


    public function copycontractstore(Request $request, Contracts $contract)
    {
        $validator = \Validator::make(
            $request->all(), [
                            'client_name' => 'required|max:20',
                            'subject' => 'required',
                            'value' => 'required',
                            'type' => 'required',
                            'start_date' => 'required',
                            'end_date' => 'required',
                        ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->route('contracts.index')->with('error', $messages->first());
        }
        $contract              = new Contracts();
        $contract->id          = $this->ContractNumber();
        $contract->client_name = $request->client_name;
        $contract->project_id  = $request->project;
        $contract->subject     = $request->subject;
        $contract->value       = $request->value;
        $contract->type        = $request->type;
        $contract->start_date  = $request->start_date;
        $contract->end_date    = $request->end_date;
        $contract->description = $request->description;
        $contract->created_by  = \Auth::user()->creatorId();
        $contract->save();

        $settings  = \Utility::settings(\Auth::user()->creatorId());
        
        if(isset($settings['contract_notification']) && $settings['contract_notification'] ==1){
            $msg = 'New Invoice '.Auth::user()->contractNumberFormat($this->ContractNumber()).'  created by  '.\Auth::user()->name.'.';
            \Utility::send_slack_msg($msg);
        }
        if(isset($settings['telegram_contract_notification']) && $settings['telegram_contract_notification'] ==1){
            $resp = 'New  Invoice '.Auth::user()->contractNumberFormat($this->ContractNumber()).'  created by  '.\Auth::user()->name.'.';
            \Utility::send_telegram_msg($resp);
        }
        
        return redirect()->route('contracts.index')->with('success', __('Contract successfully created!'));
       
    }

    public function contract_status_edit(Request $request, $id)
    { 
        $contract = Contracts::find($id);
        $contract->status   = $request->status;
        $contract->save();
       
    }

    public function signature($id)
    {
        $contract = Contracts::find($id);
    
        return view('contracts.signature', compact('contract')); 
    }


    public function signatureStore(Request $request)
    {
        $contract              = Contracts::find($request->contract_id);
        
        if(\Auth::user()->type == 'company'){
            $contract->company_signature       = $request->company_signature;
        }
        if(\Auth::user()->type == 'client'){
            $contract->client_signature       = $request->client_signature;
        }
    
        $contract->save();

        return response()->json(
            [
                'Success' => true,
                'message' => __('Contract Signed successfully'),
            ], 200
        );
        
    }



    public function pdffromcontract($contract_id)
    {
        $id = \Illuminate\Support\Facades\Crypt::decrypt($contract_id);
        $settings = Utility::settings();
        $contract  = Contracts::findOrFail($id);

        //Set your logo
        $logo=\App\Models\Utility::get_file('uploads/logo/');
        // $logo         = asset(\Storage::url('logo/'));
        $dark_logo    = Utility::GetLogo('dark_logo');
        $img = asset($logo . '/' . (isset($dark_logo) && !empty($dark_logo) ? $dark_logo : 'logo-dark.png'));
        
        return view('contracts.template', compact('contract','img','settings'));
    }
    
    public function printContract($id)
    {
        $contract  = Contracts::findOrFail($id);
        $settings = Utility::settings();
        $client   = $contract->client_name;
        //Set your logo
        // $logo         = asset(\Storage::url('logo/'));
        $logo=\App\Models\Utility::get_file('uploads/logo/');
        $dark_logo    = Utility::GetLogo('dark_logo');
        $img = asset($logo . '/' . (isset($dark_logo) && !empty($dark_logo) ? $dark_logo : 'logo-dark.png'));
        return view('contracts.contract_view', compact('contract','client','img','settings'));
            
    }


    public function sendmailContract($id,Request $request)
    {
        $contract              = Contracts::find($id);
        $contractArr = [
            'contract_id' => $contract->id,
        ];
        $client = User::find($contract->client_name);
    
        $estArr = [
            'email' => $client->email,
            'contract_subject' => $contract->subject,
            'contract_client' => $client->name,
            // 'contract_project' => $contract,
            'contract_start_date' => $contract->start_date,
            'contract_end_date' =>$contract->end_date ,
        ];
        
        // Send Email
        $resp = Utility::sendEmailTemplate('New Contract', $client->id , $estArr);
        // dd($resp);
        return redirect()->route('contracts.show', $contract->id)->with('success', __('Send successfully!') . (($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
    }

}

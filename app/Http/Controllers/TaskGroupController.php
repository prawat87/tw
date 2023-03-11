<?php

namespace App\Http\Controllers;

use App\Models\TaskGroup;
use App\Models\Projects;
use Illuminate\Http\Request;

class TaskGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::user()->can('manage task')) {
            $taskgroups = TaskGroup::where('created_by', '=', \Auth::user()->creatorId())->get();
            if (!empty($taskgroups)) {
                $taskgroups = $taskgroups;
            }
            // $taskgroups = TaskGroup::toSql();
            //dd($taskgroups);
            return view('taskgroup.index', compact('taskgroups'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::user()->can('create task')) {


            return view('taskgroup.create');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
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
        if (\Auth::user()->can('create task')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:100',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('taskgroup.index')->with('error', $messages->first());
            }

            $taskgroup             = new TaskGroup();
            $taskgroup->name       = $request->name;
            $taskgroup->created_by = \Auth::user()->creatorId();
            $taskgroup->save();

            return redirect()->route('taskgroup.index')->with('success', __('Group successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TaskGroup  $taskGroup
     * @return \Illuminate\Http\Response
     */
    public function show(TaskGroup $taskGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TaskGroup  $taskGroup
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        if (\Auth::user()->can('edit task')) {
            $taskgroup = TaskGroup::findOrfail($id);

            if ($taskgroup->created_by == \Auth::user()->creatorId()) {
                return view('taskgroup.edit', compact('taskgroup'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TaskGroup  $taskGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('edit task')) {
            $taskgroup = TaskGroup::findOrfail($id);
            if ($taskgroup->created_by == \Auth::user()->creatorId()) {

                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|max:100'
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('taskgroup.index')->with('error', $messages->first());
                }

                $taskgroup->name  = $request->name;
                $taskgroup->save();

                return redirect()->route('taskgroup.index')->with('success', __('Group name successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TaskGroup  $taskGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaskGroup $taskGroup)
    {
        //
    }
}

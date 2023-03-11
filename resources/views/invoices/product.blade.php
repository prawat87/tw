{{ Form::model($invoice, array('route' => array('invoices.products.store', $invoice->id), 'method' => 'POST')) }}
    <div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <input type="text" class="form-control " value="{{(!empty($invoice->project)?$invoice->project->name:'')}}" readonly>
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio"  name="type" value="milestone" checked="checked" onclick="hide_show(this)" checked="checked" id="customCheckinlh1">
                    <label class="form-check-label" for="customCheckinlh1">
                        {{__('Milestone & Task')}}
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="type" value="other" onclick="hide_show(this)"
                        id="customCheckinlh2">
                    <label class="form-check-label" for="customCheckinlh2">
                        {{__('Other')}}
                    </label>
                </div>
            </div>
            </div>
        </div>
        <div id="milestone">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="milestone_id" class="col-form-label">{{__('Milestone')}}</label>
                        <select class="form-select" onchange="getTask(this,{{$invoice->project_id}})" id="milestone_id" name="milestone_id">
                            <option value="" selected="selected">{{__('Select Milestone')}}</option>
                            @foreach($milestones as  $milestone)
                                <option value="{{$milestone->id}}">{{$milestone->title}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="task_id" class="col-form-label">{{__('Task')}}</label>
                        <select class="form-select" id="task_id" name="task_id">
                            <option value="" selected="selected">{{__('Select Task')}}</option>
                            @foreach($tasks as  $task)
                                <option value="{{$task->id}}">{{$task->title}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div id="other" style="display: none">
            <div id="milestone">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="title" class="col-form-label">{{__('Title')}}</label>
                            <input type="text" class="form-control " name="title">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="price" class="col-form-label">{{__('Price')}}</label>
                    <input type="number" class="form-control " name="price" step="0.01" required>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        @if(isset($invoice))
            <input type="submit" value="{{__('Add')}}" class="btn btn-primary">
        @else
            <input type="submit" value="{{__('Create')}}" class="btn btn-primary">
        @endif
        <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    </div>


{{ Form::close() }}

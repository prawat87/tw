    <div class="row">
        <div class="col-md-12">
            @foreach ($timeSheets as $date => $logs)
                <h5 class="my-3">{{ $dateFormat[$date]; }}</h5>
                <table class="table align-middle mb-0 bg-white">
                  <thead class="bg-light">
                    <tr>
                      <th>{{__('Project')}}</th>
                      <th>{{__('Who')}}</th>
                      <th>{{__('Description')}}</th>
                      <th>{{__('Task List')}}</th>
                      <th>{{__('Start')}}</th>
                      <th>{{__('End')}}</th>
                      <th>{{__('Billable')}}</th>
                      <th>{{__('Time')}}</th>
                      <th>{{__('Action')}}</th>
                    </tr>
                  </thead>
                  <tbody id="entriesList">
                    @foreach ($logs as $timeSheet)
                <tr>
                    <td>
                        <div class="ms-3">
                            <p>{{ $timeSheet['project_name'] ?? ''}}</p>
                        </div>
                    </td>
                    <td>
                      <div class="d-flex align-items-center">
                        <img src="http://localhost/teamwork/storage/productimages/{{$timeSheet['avatar']}}" alt="" style="width: 45px; height: 45px" class="rounded-circle"
                          />
                      </div>
                      <div>
                        <p class="fw-bold mb-1">{{ $timeSheet['user_name'] ?? ''}}</p>
                      </div>
                  </td>
                  <td>
                    <p class="fw-normal mb-1">Task : {{ $timeSheet['task_title'] ?? ''}}</p>
                    <p class="text-muted mb-0">{{ $timeSheet['remark'] ?? ''}}</p>
                  </td>
                  <td>
                    <p class="fw-normal mb-1">{{ $timeSheet['group_name'] ?? ''}}</p>
                  </td>
                  <!-- <td>
                    <span class="badge badge-success rounded-pill d-inline">Active</span>
                  </td> -->
                  <td>{{ $timeSheet['start_time'] ?? '' }}</td>
                  <td>{{ $timeSheet['end_time'] ?? '' }}</td>
                  <td>
                    @if($timeSheet['billable'] == 'Yes')
                      <img src="{{URL::asset('public/assets/images/icons/accept.png')}}" alt="Yes" height="20" width="20" style="margin-left: 20px;">
                    @else
                      <img src="{{URL::asset('public/assets/images/icons/reject.png')}}" alt="No" height="20" width="20" style="margin-left: 20px;">
                    @endif
                  </td>
                  <td>{{ $timeSheet['total_hrs_mins'] ?? ''}}</td>
                    @if(\Auth::user()->type!='client')
                        <td class="Action">
                            <div class="action-btn bg-info ms-2">
                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"  data-url="{{ route('task.timesheet.edit',[$timeSheet['id']]) }}" data-ajax-popup="true" data-title="{{__('Edit Time Sheet')}}" title="{{__('Edit')}}" data-bs-toggle="tooltip" data-bs-placement="top" data-size="md"><span class="text-white"><i class="ti ti-edit"></i></span></a>
                            </div>
                            <div class="action-btn bg-danger ms-2">
                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$timeSheet['id']}}" title="{{__('Delete')}}" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white"><i class="ti ti-trash"></i></span></a>
                            </div>                                            {!! Form::open(['method' => 'DELETE', 'route' => ['task.timesheet.destroy', $timeSheet['id']],'id'=>'delete-form-'.$timeSheet['id']]) !!}
                            {!! Form::close() !!}
                        </td>
                    @else
                        <td>{{ $timeSheet['user_name'] ?? '' }}</td>
                    @endif
                </tr>
                @endforeach
              </tbody>
            </table>
            @if( !empty($finalTime) )
              <p class="my-3" style="text-align: right"><strong>Total: </strong> {{ (!empty($finalTime[$date]) ) ? floor($finalTime[$date] / 60).'h ' : '' }} {{ (!empty($finalTime[$date])) ? (($finalTime[$date] - floor($finalTime[$date] / 60) * 60) > 0) ? ($finalTime[$date] - floor($finalTime[$date] / 60) * 60) . 'm' : '' : '' }} <strong>  Billable Time: </strong> {{ (!empty($totalBillableTimeSum[$date])) ? floor($totalBillableTimeSum[$date] / 60).'h ' : '0h' }} {{ (isset($totalBillableTimeSum[$date])) ? (($totalBillableTimeSum[$date] - floor($totalBillableTimeSum[$date] / 60) * 60) > 0) ? ($totalBillableTimeSum[$date] - floor($totalBillableTimeSum[$date] / 60) * 60) . 'm' : '' : '' }}</p>
            @endif
            @endforeach
        </div>
    </div>

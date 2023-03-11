
{{-- @dd('bgahjsa'); --}}
<div>
    <ul class="list-unstyled">
        <li class="mb-2"><strong class="text-dark">{{ __('Title')}} :</strong> &nbsp; {{$bug->title}}</li>
        <li class="mb-2"><strong class="text-dark">{{ __('Priority')}} :</strong> &nbsp;
            {{ucfirst($bug->priority)}}</li>
        <li class="mb-2"><strong class="text-dark">{{ __('Description')}} :</strong> &nbsp; {{$bug->description}}</li>
        <li class="mb-2"><strong class="text-dark">{{ __('Created Date')}} :</strong> &nbsp; {{$bug->created_at}}</li>
        <li class="mb-2"><strong class="text-dark">{{ __('Assign to')}} :</strong> &nbsp; {{(!empty($bug->assignTo)?$bug->assignTo->name:'')}}</li>
    </ul>
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active text-uppercase" id="comment-tab" data-bs-toggle="tab" href="#comment" role="tab" aria-controls="comment" aria-selected="true">{{__('Comments')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-uppercase" id="files-tab" data-bs-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="false">{{__('Files')}}</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="comment" role="tabpanel" aria-labelledby="comment-tab">
                    <div class="form-group m-0">
                        <form method="get" id="form-comment" data-action="{{route('bug.comment.store',[$bug->project_id,$bug->id])}}">
                            <textarea class="form-control" name="comment" placeholder="{{ __('Write message')}}" id="example-textarea" rows="3" required></textarea>
                            <br>
                            <div class="text-end mt-10">
                                <div class="btn-group mb-2 ml-2 d-none d-sm-inline-block">
                                    <button type="submit" class="btn btn-primary">{{ __('Submit')}}</button>
                                </div>
                            </div>
                        </form>
                        <div class="comment-holder" id="comments">
                            @foreach($bug->comments as $comment)
                                <div class="media">
                                    <div class="media-body">
                                        <div class="d-flex justify-content-between align-items-end">
                                            <div>
                                                <h5 class="mt-0">{{(!empty($comment->user)?$comment->user->name:'')}}</h5>
                                                <p class="mb-0 text-xs">{{$comment->comment}}</p>
                                            </div>
                                            <a href="#" class="btn btn-outline btn-sm text-danger delete-comment" data-url="{{route('bug.comment.destroy',$comment->id)}}">
                                                <i class="ti ti-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">
                    <div class="form-group m-0">
                        <form method="get" id="form-file" enctype="multipart/form-data" data-url="{{ route('bug.comment.file.store',$bug->id) }}">
                            @csrf
                            <div class="choose-file form-group">
                                <label for="file" class="form-control-label">
                                    <div>{{__('Choose file here')}}</div>
                                    <input type="file" class="form-control" name="file" id="file"  onchange="document.getElementById('imgs').src = window.URL.createObjectURL(this.files[0])">
                                    <img src="" id="imgs" class="mt-2" width="25%"/>
                                </label>
                                <p class="file_update"></p>
                            </div>
                            <span class="invalid-feedback" id="file-error" role="alert"></span>
                            <div class="text-end mt-10">
                                <div class="btn-group mb-2 ml-2 d-none d-sm-inline-block">
                                    <button type="submit" class="btn btn-primary">{{ __('Upload')}}</button>
                                </div>
                            </div>
                        </form>
                        <div class="row my-3" id="comments-file">
                            @foreach($bug->bugFiles as $file)
                                <div class="col-8 mb-2 file-{{$file->id}}">
                                    <h5 class="mt-0 mb-1 font-weight-bold text-sm"> {{$file->name}}</h5>
                                    <p class="m-0 text-xs">{{$file->file_size}}</p>
                                </div>
                                <div class="col-4 mb-2 file-{{$file->id}}">
                                    <div class="comment-trash" style="float: right">
                                        <a download href="{{asset(Storage::url('bugs/'.$file->file))}}" class="btn btn-outline btn-sm text-primary m-0 px-2">
                                            <i class="ti ti-download"></i>
                                        </a>
                                        <a href="#" class="btn btn-outline btn-sm red text-danger delete-comment-file m-0 px-2" data-id="{{$file->id}}" data-url="{{route('bug.comment.file.destroy',[$file->id])}}">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                    </div>
                                </div>

                                
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

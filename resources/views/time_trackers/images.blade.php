<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLongTitle">{{ $tracker->project_task}} <small>( {{$tracker->total}}, {{date('d M',strtotime($tracker->start_time))}} )</small></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"
        aria-label="Close"></button>
</div>
<div>
    <div class="row ">
        <div class="col-lg-12 product-left">
            @if( $images->count() > 0)
            <div class="swiper-container product-slider">
                <div class="swiper-wrapper">
                    @foreach ($images as $image)
                        <div class="swiper-slide" id="slide-{{$image->id}}">
                            <img src="{{  \App\Models\Utility::get_file($image->img_path)}}" alt="..."  class="img-fluid">
                            <div class="time_in_slider mt-3">{{date('H:i:s, d M ',strtotime($image->time))}} | 
                                <a href="#" class="bs-pass-fn-call"  data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-id="{{$image->id}}" title="{{__('Delete')}}" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-danger"><i class="ti ti-trash"></i></span></a>
                                
                            </div>
                        </div>
                    @endforeach            
                </div>
                <div class="swiper-button-next"></div>time tracker


                <div class="swiper-button-prev"></div>
            </div>
            <hr>
            <div class="swiper-container product-thumbs">
                <div class="swiper-wrapper">
                    @foreach ($images as $image)
                    <div class="swiper-slide" id="slide-thum-{{$image->id}}">
                        <img src="{{  \App\Models\Utility::get_file($image->img_path)}}" alt="..." class="img-fluid">
                    </div>
                    @endforeach 
                
                </div>
            </div>
            @else
            <div class="no-image">
                <h5 class="text-muted">{{__('Images Not Available.')}}</h5>
            </div>
            @endif
        </div>
    </div>
</div>
<script type="text/javascript">
    $('[data-confirm-delete]').each(function () {
    var me = $(this),
        me_data = me.data('confirm-delete');
    me_data = me_data.split("|");
    me.fireModal({
        title: me_data[0],
        body: me_data[1],
        buttons: [
            {
                text: me.data('confirm-text-yes') || 'Yes',
                class: 'btn btn-sm btn-danger rounded-pill',
                handler: function (modal) {
                    eval(me.data('confirm-yes'));
                    $.destroyModal(modal);
                }
            },
            {
                text: me.data('confirm-text-cancel') || 'Cancel',
                class: 'btn btn-sm btn-secondary rounded-pill',
                handler: function (modal) {
                    $.destroyModal(modal);
                    eval(me.data('confirm-no'));
                }
            }
        ]
    })
});
</script>
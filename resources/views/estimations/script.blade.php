{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('custom/js/html2pdf.bundle.min.js') }}"></script>

<script>
    function closeScript() {
        setTimeout(function () {
            window.open(window.location, '_self').close();
        }, 1000);
    }

    $(window).on('load', function () {
        var element = document.getElementById('boxes');
        var opt = {
            filename: '{{\App\Models\Utility::estimateNumberFormat($estimation->estimation_id)}}',
            image: {type: 'jpeg', quality: 1},
            html2canvas: {scale: 4, dpi: 72, letterRendering: true},
            jsPDF: {unit: 'in', format: 'A4'}
        };

        html2pdf().set(opt).from(element).save().then(closeScript);
    });
</script> --}}




<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('custom/js/html2pdf.bundle.min.js') }}"></script>

@auth('web')
    <?php $url =route('estimations.show',$estimation->id); ?>

@endauth

@if(!\Auth::check())
  <?php $urlnonauth =route('pay.estimation',\Illuminate\Support\Facades\Crypt::encrypt($estimation->id));?>
@endif

<script>
  'use strict';

function closeScript() {

        @if( \Auth::guard('web')->check())

        setTimeout(function () {
            window.location.href = '{{ $url }}';
        }, 1000);

    @else
        setTimeout(function () {
            window.location.href = '{{ $urlnonauth }}';
        }, 1000);

    @endif

}

    $(window).on('load', function () {
        var element = document.getElementById('boxes');
        var opt = {
            filename: '{{\App\Models\Utility::estimateNumberFormat($estimation->estimation_id)}}',
            image: {type: 'jpeg', quality: 1},
            html2canvas: {scale: 4, dpi: 72, letterRendering: true},
            jsPDF: {unit: 'in', format: 'A4'}
        };

        html2pdf().set(opt).from(element).save().then(closeScript);
    });
</script>
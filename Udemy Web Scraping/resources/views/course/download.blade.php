@extends('layouts.single')

@section('head-tags')
<style>
.imgbannerlink {
    margin: auto;
    max-width: 100px;
}

#file-link {
    cursor: not-allowed;
    display: none;
}

#link-timer-text {
    font-size: 16px;
}

#link-timer {
    border-radius: 50%;
}

</style>
@endsection

@section('content')

<div class="row">
    <div class="col-md-3">
    <iframe src="//rcm-na.amazon-adsystem.com/e/cm?o=1&p=12&l=ur1&category=electronics&banner=0A5SH6Q864P0BH9N6882&f=ifr&linkID=45ac2189306a3ef5e4ddf8ee7c97e1f7&t=learn4free0b-20&tracking_id=learn4free0b-20" width="300" height="250" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0"></iframe>
    </div>

    <div class="col-md-6">
        <div class="m-5 text-center">
            <div class="border border-danger p-5 text-danger my-5">
                <strong>Warning</strong>: Some of our course have been removed due to copyright issues by the authorities. So, try other courses if this course link is dead. <br>Thank You!
            </div>
            <h6 class="mb-4">[Learn4Free]Udemy-{{ $course->name }}.zip</h6>
            <div class="clearfix"></div>
            <script data-cfasync='false' type='text/javascript' src='//p423273.clksite.com/adServe/banners?tid=423273_829953_3'></script> 
            <p class="text-secondary my-5" id="link-timer-text">The link will appear in <strong id="link-timer" class="text-info border border-info px-3 py-3">30</strong> seconds.</p>
            <a id="file-link" class="btn btn-sm btn-disabled mt-5">Download File</a>               
        </div>

    </div>

    <div class="col-md-3">
        <iframe src="//rcm-na.amazon-adsystem.com/e/cm?o=1&p=12&l=ur1&category=pets&banner=1RJ5QAT5B55ECPXSXB82&f=ifr&linkID=c5a60a83512fdc68fcd255366fd2f8f4&t=learn4free0b-20&tracking_id=learn4free0b-20" width="300" height="250" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0"></iframe>
    </div>
</div>

<script>
    var t=30;

    function calc(t) {
        setTimeout(() => {
            $('#link-timer').html(t--);
            if(t == 8) {
                $('#link-timer').toggleClass('py-3 py-2');
            }
            if(t) {
                calc(t);
            }

            else {
                setTimeout(() => {
                    $('#link-timer').html(0);
                    $.post("{{ url('/') }}/api/getlink",{id: {{ $course->id }}}
                    ).done(function(data){
                        $('#file-link').attr('href',data.url);
                        $('#file-link').css('cursor','pointer');
                        $('#file-link').removeClass('btn-disabled');
                        $('#file-link').addClass('btn-primary');
                        $('#file-link').show();
                        $('#link-timer-text').hide();
                    });
                },1000);
            }
        }, 1000);
    }
    calc(t);
</script>

@endsection
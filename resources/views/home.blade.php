@extends('layouts.app')

@section('title', 'Dashboard')

@section('styles')
<link href="/css/lib/sweetalert/sweetalert.css" rel="stylesheet">
<link href="/css/lib/chartist/chartist.min.css" rel="stylesheet">
<style type="text/css">
    #legends ul {
        margin-left: 30px;
    }

    #legends li {
        list-style-type: none;
    }

    #legends li:before {
      content: "◼︎ ";
    }

    .ct-bar-chart .ct-bar {
        stroke-width: 30px
    }

    .ct-series-a .ct-bar, .ct-series-a .ct-line, .ct-series-a .ct-point, .ct-series-a .ct-slice-donut {
        stroke: #4680ff;
    }

    .ct-series-b .ct-bar, .ct-series-b .ct-line, .ct-series-b .ct-point, .ct-series-b .ct-slice-donut {
        stroke: #ffcc00;
    }

    .ct-series-c .ct-bar, .ct-series-c .ct-line, .ct-series-c .ct-point, .ct-series-c .ct-slice-donut {
        stroke: #26DAD2;
    }

    .ct-series-d .ct-bar, .ct-series-d .ct-line, .ct-series-d .ct-point, .ct-series-d .ct-slice-donut {
        stroke: #ff4646;
    }

    .menunggu li:before {
        color: #4680ff;
    }

    .proses li:before {
        color: #ffcc00;
    }

    .selesai li:before {
        color: #26DAD2;
    }

    .ditolak li:before {
        color: #ff4646;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-block">
                <h4 class="card-title">Dashboard</h4>
                @if (session('message'))
                    <div class="m-t-40 alert alert-{{ session('type') }}">
                    {!! session('message') !!}
                    </div>
                @endif
                <div class="ct-bar-chart"></div>
                <div class="row" id="legends">
                    <div class="col-md-3">
                        <ul class="menunggu">
                            <li>Menunggu</li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <ul class="proses">
                            <li>Proses</li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <ul class="selesai">
                            <li>Selesai</li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <ul class="ditolak">
                            <li>Ditolak</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="/js/lib/chartist/chartist.min.js"></script>
    <script src="/js/lib/chartist/chartist-plugin-tooltip.min.js"></script>
    <script type="text/javascript">
        $(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route("permohonan.stats") }}',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var dt = {
                        labels: [],
                        series: [
                            [],
                            [],
                            [],
                            []
                        ]
                    };

                    $.each(data.layanan, function(key, value) {
                        dt.labels.push(value.id_layanan);
                        dt.series[0].push(value.permohonan_menunggu_count);
                        dt.series[1].push(value.permohonan_proses_count);
                        dt.series[2].push(value.permohonan_selesai_count);
                        dt.series[3].push(value.permohonan_ditolak_count);
                    });

                    var options = {
                        height: 300,
                        seriesBarDistance: 30,
                        axisY: {
                            onlyInteger: true,
                            scaleMinSpace: 1,
                        }
                    };

                    var responsiveOptions = [
                      ['screen and (max-width: 640px)', {
                                seriesBarDistance: 5,
                                axisX: {
                                    labelInterpolationFnc: function (value) {
                                        return value[0];
                                    }
                                },
                                axisY: {
                                    onlyInteger: true,
                                    scaleMinSpace: 1,
                                    labelInterpolationFnc: function(value) {
                                        console.log(value);
                                        return value;
                                    }
                                }
                      }]
                    ];

                    new Chartist.Bar('.ct-bar-chart', dt, options, responsiveOptions);
                }
            });
        });
    </script>
@endsection
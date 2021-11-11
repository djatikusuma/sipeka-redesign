@extends('layouts.print')
@section('meta_title') {{ $event->topic }} @endsection

@section('content')

    <div class="col-lg-12 p-0 m-0">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <div class="row mb-2 mt-5">
                        <div class="col-md-1">
                            <img src="http://103.122.5.201/fasilitator/public/uploads/images/provinsi.png" alt="SIPEKA"
                                width="75" />
                        </div>


                        <div class="col-md-10 text-center">

                            <h5 class="m-0 p-0"> PEMERINTAH DAERAH PROVINSI JAWA BARAT </h5>
                            <h1 class="m-0 p-0"> DINAS PERKEBUNAN </h1>
                            <h5 class="m-0 p-0"> {{ $event->topic }} </h5>
                            <h7 class="m-0 p-0">
                                {{ Carbon\Carbon::parse($event->meeting_date)->format('d F Y H:i') }}
                            </h7>
                        </div>
                    </div>

                </div>
                <hr style="border: 1px solid #000" class="p-0 mt-1">
                <div class="table-rep-plugin mt-7">
                    <div class="table-responsive mb-0" data-pattern="priority-columns">
                        <table id="datatable" class="table table-striped table-bordered table-rounded"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%">
                            <thead style="border: 1px solid #eeeeee">
                                <tr class="text-center text-uppercase fw-bolder fs-7">
                                    <th>No</th>
                                    @foreach (json_decode($event->field_json) as $form)
                                        <th>{{ $form->label }}</th>
                                    @endforeach
                                    <th>Tanda Tangan</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>

                            <tbody  style="border: 1px solid #eeeeee">
                                @foreach ($event->presences as $data)
                                    @php $field = json_decode($data->form_json) @endphp
                                    <tr>
                                        <td class="text-center"a>{{ $loop->index + 1 }}</td>
                                        @foreach ($field as $key => $value)
                                            @if ($key == 'ttd')
                                                <td class="text-center"a><img src="data:{{ $value }}"
                                                    style="width:60px;max-height:20px" /></td>
                                            @else
                                                <td>{{ $value }}</td>
                                            @endif
                                        @endforeach
                                        <td class="text-center"a>
                                            {{ Carbon\Carbon::parse($data->created_at)->format('d F Y H:i') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

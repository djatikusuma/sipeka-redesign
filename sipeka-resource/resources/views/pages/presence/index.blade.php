@extends('layouts.guest')
@section('meta_title', 'Manajemen Kegiatan')


@section('content')
<div class="card mb-5 mb-xl-8">
  <div class="card-header border-0 pt-5">
    <h3 class="card-title align-items-start flex-column">
      <span class="card-label fw-bolder fs-3 mb-1">{{ $event->topic }}</span>
    </h3>
  </div>
  <div class="card-body">
    @include('pages.presence._table', [
        'fields' => json_decode($event->field_json),
        'column' => $column
    ])
  </div>
</div>

@endsection

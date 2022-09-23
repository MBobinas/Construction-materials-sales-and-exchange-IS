@extends('layouts.admin')
@section('content')
<div class="container">
    {{ Breadcrumbs::render('admin.profile.index', $user) }}
    <div class="row">
        <div class="col-3 p-5">
            <img src="/storage/{{ $user->profile->image }}" class="img-responsive; rounded-circle w-100">
        </div>
        <div class="col-9 pt-6">
            <div class="d-flex justify-content-between align-items-baseline">
                <h3>{{ $user->email }}</h3>
            </div>

            @can('update', $user->profile)
                <a href="{{ route('admin.profile.edit', $user->id) }}">Redaguoti Profilį</a>
            @endcan

        <div class="pt-4 font-weight-bold">{{ $user->profile->description }}</div>
        <div>{{ $user->profile->phone }}</div>
    </div>
</div>
@endsection

@section('scripts')
@parent
@endsection
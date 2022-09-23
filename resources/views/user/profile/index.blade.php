@extends('layouts.user')
@section('content')
    <div class="container">
        {{ Breadcrumbs::render('user.profile.index', $user) }}
        <div class="row">
            <div class="col-3 p-5">
                @if ($user->profile->image == null)
                    <img src="{{ asset('images/placeholder.png') }}" class="img-responsive; rounded-circle w-100">
                @else
                    <img src="/storage/{{ $user->profile->image }}" class="img-responsive; rounded-circle w-100">
                @endif
            </div>
            <div class="col-9 pt-6">
                <div class="d-flex justify-content-between align-items-baseline">
                    <h3>{{ $user->email }}</h3>
                </div>

                @can('update', $user->profile)
                    <a href="{{ route('user.profile.edit', $user->id) }}">Redaguoti Profilį</a>
                @endcan

                <div class="pt-4 text-wrap">
                    <span class="fw-bold">Aprašymas:</span> {{ $user->profile->description }}
                </div>
                <div>
                    <span class="fw-bold">Tel. Nr:</span>
                    {{ $user->profile->phone }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
@endsection

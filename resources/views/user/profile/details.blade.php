@extends('layouts.user')
@section('content')

<nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ url()->previous() }}">Atgal</a>
        </li>
    </ol>
    @if (session('success'))
        <div class="alert alert-success mb-1">
            {{ session('success') }}
        </div>
    @endif
</nav>

<div class="container">
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

        <div class="pt-4 font-weight-bold">{{ $user->profile->description }}</div>
        <div>{{ $user->profile->phone }}</div>
    </div>
</div>
@endsection

@section('scripts')
@parent
@endsection
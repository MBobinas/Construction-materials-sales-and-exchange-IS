@include('layouts.landing.head')
@include('layouts.landing.nav')
@include('layouts.landing.help')
@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
@include('layouts.landing.contact')
@include('layouts.landing.footer')
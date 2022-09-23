@extends('layouts.user')
@section('content')

    <head>
        <script src="https://kit.fontawesome.com/477f391cf1.js" crossorigin="anonymous"></script>
    </head>

    <body>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    {{ Breadcrumbs::render('user.comments.index') }}
                </li>
            </ol>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </nav>


        <div class="container-fluid">
            <div class="col-md-11 text-center">
                <h4>Gauti atsiliepimai</h4>
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{ route('user.comments.index') }}" class="btn btn-outline-primary active"
                        aria-current="page">Gauti Atsiliepimai</a>
                    <a href="{{ route('user.comments.sentComments') }}" class="btn btn-outline-primary">Išsiųsti atsliepimai</a>
                </div>
            </div>

            @if($comments->count() > 0)
            <div class="d-flex row">
                <div class="col-md-1">
                </div>
                <div class="col-md-10 d-grid gap-2">
                    @foreach ($comments as $comment)
                        <div class="card">
                            <div class="card-body p-2">
                                <h5 class="text-md-start text-wrap mb-2 pb-2" style="color:#28282B;">Komentaras po skelbimu:
                                    <a href="/skelbimai/{{ $comment->listing->id }}">{{ $comment->listing->title }}</a>
                                </h5>
                                <div class="row">
                                    <div class="col">
                                        <!-- comment content start -->
                                        <div class="d-flex flex-start">
                                            <a href="{{ route('user.profile.details', $comment->user_id) }}">
                                                <img class="rounded-circle shadow-1-strong me-3 mb-1"
                                                    src="{{ asset('/storage/' . $comment->user->profile->image) }}"
                                                    alt="profilio nuotrauka" width="65" height="65" />
                                            </a>
                                            <div class="flex-grow-1 flex-shrink-1">
                                                <div class="border-bottom">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <p class="mb-1">
                                                            <a href="{{ route('user.profile.details', $comment->user_id) }}">
                                                                {{ $comment->author }} </a>
                                                            <span class="small">-
                                                                {{ $comment->created_at->diffForHumans() }}</span>
                                                        </p>
                                                    </div>
                                                    <p class="small mb-0">
                                                        {{ $comment->text }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- comment content end -->
                                    </div>
                                </div>
                            </div>
                            <form action="/atsakas/skelbimas/{{ $comment->listing->id }}" method="POST">
                                @csrf
                                <div class="row">
                                    <h5 class="text-center" style="color:#28282B;">Atsakyti į komentarą</h5>
                                    <div class="col-2">

                                    </div>
                                    <div class="col-8 text-center">
                                        <input type="hidden" name="author" class="form-control"
                                            value="{{ Auth::User()->name }}" required>

                                        <div class="col-md-12 border rounded p-2 mb-1" id="test">
                                            <textarea class="form-control textarea" id="comment" onkeyup="validate()" placeholder="Rašykite komentarą..." rows="1"
                                                style="resize: none;" name="comment"></textarea>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <span id="words_count" class="d-none text-primary"><span
                                                    id="textcount"></span>/70</span>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="d-flex flex-row justify-content-center mt-3 me-4">
                                            <button class="btn btn-primary float-right" name="submit" type="submit"
                                                id="submit_comment">
                                                <i class="fas fa-paper-plane"></i>
                                                <span>Atsakyti</span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endforeach
                    <div class="col-md-1">
                    </div>
                    <!--comments end-->
                @else
                    <div class="col-md-11 text-info mt-2 text-center">
                        <div class="card">
                            <div class="card-body text-center text-wrap ">
                                <h5>Neturite gautų atsiliepimų</h5>
                            </div>
                        </div>
                    </div>
                @endif

                </div>

                <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
                                integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
                                crossorigin="anonymous"></script>
                <script src="{{ asset('assets/js/accordion.js') }}"></script>
    </body>

    <script>
        function validate() {
            const areabox = document.querySelector('#test');
            const areatext = document.querySelector("#comment").value.length;
            const textcount = document.querySelector("#textcount");
            const wordcount = document.querySelector("#words_count");
            textcount.innerHTML = areatext;


            if (areatext > 70) {
                textcount.classList.add("text-danger");
                areabox.classList.add("border-danger");
            } else {
                textcount.classList.remove("text-danger");
                areabox.classList.remove("border-danger");
            }

            if (areatext < 1) {
                wordcount.classList.add("d-none");
            } else {
                wordcount.classList.remove("d-none");
            }
        }
    </script>
@endsection
@section('scripts')
    @parent
@endsection

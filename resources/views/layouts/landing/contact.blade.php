<section class="contact" style="background-color: #fff">
    <div class="container">
        <form action="" method="post" action="{{ route('main.landingPage.store') }}">
            @csrf
            <div class="row align-items-center justify-content-center">
                <div class="col-md-5">
                    <h1>Susisiekite su mumis</h1>
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Elektroninis paštas</label>

                        <input type="email" class="form-control {{ $errors->has('email') ? 'error' : '' }}" id="email"
                            placeholder="pastas@pavyzdis.com" name="email" />
                        @if ($errors->has('email'))
                            <div class="alert alert-danger">
                                <div class="error">
                                    {{ $errors->first('email') }}
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="form-group mb-3">
                        <label for="subject" class="form-label">Klausimo tema</label>

                        <input type="text" class="form-control {{ $errors->has('subject') ? 'error' : '' }}"
                            id="subject" placeholder="Dėl..." name="subject" />
                        @if ($errors->has('subject'))
                            <div class="alert alert-danger">
                                <div class="error">
                                    {{ $errors->first('subject') }}
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="form-group mb-3">
                        <label for="message" class="form-label">Klausimas</label>
                        <textarea class="form-control {{ $errors->has('message') ? 'error' : '' }}" id="message" rows="3"
                            placeholder="Žinutė..." style=" resize: none;" name="message"></textarea>
                        @if ($errors->has('message'))
                            <div class="alert alert-danger">
                                <div class="error">
                                    {{ $errors->first('message') }}
                                </div>
                            </div>
                        @endif
                    </div>
                    <input type="submit" name="send" value="Pateikti" class="btn btn-outline-secondary" />
                </div>
                <div class="col-md-5">
                    <img src="{{ asset('images/Questions-bro.svg') }}" alt="Contact us">
                </div>
            </div>
        </form>
    </div>
</section>

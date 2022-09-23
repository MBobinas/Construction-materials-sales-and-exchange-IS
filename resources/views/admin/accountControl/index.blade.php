@extends('layouts.admin')
@section('content')

    <head>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

        <style>
            body {
                font-family: roboto;
            }
    
            @media only screen and (max-width:800px) {
                #no-more-tables tbody,
                #no-more-tables tr,
                #no-more-tables td {
                    display: block;
                }
                #no-more-tables thead tr {
                    position: absolute;
                    top: -9999px;
                    left: -9999px;
                }
                #no-more-tables td {
                    position: relative;
                    padding-left: 50%;
                    border: none;
                    border-bottom: 1px solid #eee;
                }
                #no-more-tables td:before {
                    content: attr(data-title);
                    position: absolute;
                    left: 6px;
                    font-weight: bold;
                }
                #no-more-tables tr {
                    border-bottom: 1px solid #ccc;
                }
            }

            button {
                    background-color: transparent;
                    background-repeat: no-repeat;
                    border: none;
                    cursor: pointer;
                    overflow: hidden;
                    outline: none;
            }
    
        </style>


    </head>
    <body>
        <nav>
            <ol class="breadcrumb">
                {{ Breadcrumbs::render('admin.accountControl.index')}}
            </ol>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </nav>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5 class="panel-title text-dark">Sistemoje registruotų naudotojų lentelė</h5>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive" id="no-more-tables">
                                    <table class="display compact cell-border" style="width: 100%" id="datatable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>ID</th>
                                                <th>Vardas</th>
                                                <th>El. Paštas</th>
                                                <th>Gimimo data</th>
                                                <th>Rolė</th>
                                                <th>Registracijos data</th>
                                                <th>Statusas</th>
                                                <th>Veiksmas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($users as $user)
                                                <tr class="table-primary">
                                                    <td data-title="ID" class="fw-bold text-center">{{ $user->id }}</td>
                                                    <td data-title="Vardas">{{ $user->name }}</td>
                                                    <td data-title="El. Paštas">{{ $user->email }}</td>
                                                    @if ($user->profile->birth_date != null)
                                                        <td data-title="Gimimo data" class="text-center">{{ $user->profile->birth_date }}</td>
                                                    @else
                                                        <td data-title="Gimimo data" class="text-center">Nepateikta</td>
                                                    @endif
                                                    @foreach($user->roles as $role)
                                                        <td data-title="Rolė">{{ $role->display_name }}</td>
                                                    @endforeach
                                                    <td data-title="Registracijos data" class="text-center">
                                                        {{ $user->created_at->format('Y/m/d H:i') }}</td>
                                                    <td data-title="Statusas" class="text-center">
                                                        {{ $user->blocked_at != null ? 'Užblokuotas' : 'Aktyvus' }}
                                                    </td>
                                                    <td data-title="Veiksmas" class="text-center">                                   
                                                        @if($user->blocked_at != null)
                                                    <form action="{{ route('admin.accountControl.unblock', $user->id) }}" method="POST" onsubmit="return submitUnblock(this);">
                                                        @csrf
                                                        @method('PUT')    
                                                        <a href="{{ route('user.profile.details', $user->id) }}"><i class="fa-solid fa-eye text-info pe-2" title="Detaliau"></i></a>
                                                        <button type="submit">
                                                            <i class="fa-solid fa-unlock text-success" title="Atblokuoti"></i>
                                                        </button>                                        
                                                    </form>
                                                        @else
                                                    <form action="{{ route('admin.accountControl.block', $user->id) }}" method="POST" onsubmit="return submitBlock(this);">
                                                        @csrf
                                                        @method('PUT')
                                                        <a href="{{ route('user.profile.details', $user->id) }}"><i class="fa-solid fa-eye text-info pe-2" title="Detaliau"></i></a>                                                   
                                                        <button type="submit">
                                                            <i class="fa-solid fa-ban text-danger" title="Blokuoti"></i>
                                                        </button>
                                                    </form>
                                                        @endif                                                                                         
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
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
                integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
        </script>

<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/lt.json",
            },
            scrollX: true,
            columnDefs: [{
                    "orderable": false,
                    "targets": [6, 7]
                },
            ],
        });
    });
</script>

<script>
    function submitBlock(form) {
        Swal.fire({
            title: "Ar tikrai norite užblokuoti naudotoją?",
            icon: "question",
            buttons: true,
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Taip',
            cancelButtonText: 'Atgal'
        }).then(function(result) {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Naudotojas užblokuotas',
                    timer: 2500,
                    icon: 'success',
                    showConfirmButton: false,
                }).then(function() {
                    form.submit();
                });
            }
        });
        return false;
    }
</script>

<script>
    function submitUnblock(form) {
        Swal.fire({
            title: "Ar tikrai norite atblokuoti naudotoją?",
            icon: "question",
            buttons: true,
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Taip',
            cancelButtonText: 'Atgal'
        }).then(function(result) {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Naudotojas atblokuoti',
                    timer: 2500,
                    icon: 'success',
                    showConfirmButton: false,
                }).then(function() {
                    form.submit();
                });
            }
        });
        return false;
    }
</script>

    </body>
@endsection
@section('scripts')
    @parent
@endsection

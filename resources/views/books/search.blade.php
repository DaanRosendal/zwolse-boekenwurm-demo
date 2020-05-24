@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <a href="javascript:history.back()" class="btn btn-success mb-3"><i class="fas fa-chevron-circle-left fa-lg"></i> &nbsp; Terug</a>
                <a href="{{route('books.create')}}" class="btn btn-success mb-3 float-sm-right"><i class="fas fa-plus-square fa-lg"></i> &nbsp; Aanmaken</a>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{session('success')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card text-center">
                    <div class="card-header text-left h3">
                        Boeken zoeken
                    </div>
                    <div class="card-body">
                        @if(old('q'))
                            @if($bookCount == 1)
                                <h4 class="mb-3 text-left">Gezocht op: <b>{{old('q')}}</b> <span class="badge badge-success badge">{{$bookCount}} Resultaat</span></h4>
                            @else
                                <h4 class="mb-3 text-left">Gezocht op: <b>{{old('q')}}</b> <span class="badge badge-success badge">{{$bookCount}} Resultaten</span></h4>
                            @endif
                        @endif
                        <form method="GET" action="{{ route('books.search') }}">
                            <div class="input-group mb-3">
                                <input required type="text" name="q" class="form-control"
                                       placeholder="Zoek op ID, titel, auteur of categorie">
                                <div class="input-group-append">
                                    <button class="btn btn-success" type="submit">Zoek &nbsp; <i class="fas fa-search fa-lg"></i></button>
                                </div>
                            </div>
                        </form>

                        @if($bookCount != 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover text-left">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Titel</th>
                                        <th scope="col">Auteur</th>
                                        <th scope="col">Categorie</th>
                                        <th scope="col">Locatie</th>
                                        <th scope="col" style="width: 200px">Acties</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($books as $book)
                                        <tr>
                                            <th scope="row" class="align-middle">{{$book->id}}</th>
                                            <td class="align-middle">{{$book->title}}</td>
                                            <td class="align-middle">{{$book->author}}</td>
                                            <td class="align-middle">
                                                <a href="/categories/{{$book->category_id}}">{{$book->category}}</a>
                                            </td>
                                            <td class="align-middle">{{$book->location}}</td>
                                            <td class="align-middle">
                                                <span class="list-group list-group-horizontal">
                                                    @if($book->sold == false)
                                                        <a title="Markeer als verkocht" href="{{route('books.sold', $book)}}" class="list-group-item list-group-item-action text-center p-1" style="width: 50px;">
                                                                <i class="fas fa-euro-sign fa-lg ml-1 mr-1 text-success"></i></a>
                                                    @else
                                                        <a title="Markeer als niet verkocht" href="{{route('books.unsold', $book)}}" class="list-group-item list-group-item-action text-center p-1" style="width: 50px;">
                                                                <i class="fab fa-creative-commons-nc-eu fa-lg ml-1 mr-1 text-danger"></i></a>
                                                    @endif

                                                    @if($book->bol_link != null || $book->bol_link != '')
                                                        <a title="Bol.com link" target="_blank" href="{{$book->bol_link}}" class="list-group-item list-group-item-action text-center p-1" style="width: 50px;">
                                                            <i class="fas fa-external-link-alt fa-lg ml-1 mr-1 text-primary"></i></a>
                                                    @else
                                                        <a class="list-group-item list-group-item-action text-center p-1" style="width: 50px;">
                                                            <i class="fas fa-external-link-alt fa-lg ml-1 mr-1 text-disabled"></i></a>
                                                    @endif
                                                        <a title="Boek aanpassen" href="{{route('books.edit', $book)}}" class="list-group-item list-group-item-action text-center p-1" style="width: 50px;">
                                                            <i class="fas fa-edit fa-lg ml-1 mr-1 text-primary"></i></a>
                                                        <a title="Boek verwijderen" href="{{route('books.destroy', $book)}}" class="list-group-item list-group-item-action text-center p-1" style="width: 50px;"
                                                           onclick="return confirm('Weet je zeker dat je het boek \'{{$book->title}}\' van \'{{$book->author}}\' wilt verwijderen?')">
                                                            <i class="fas fa-trash-alt fa-lg text-danger ml-1 mr-1"></i>
                                                        </a>
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                Geen ID, titel, auteur of categorie gevonden die overeenkomt met "{{old('q')}}"

                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <a href="{{route('books.search')}}" class="btn btn-success mb-3"><i class="fas fa-search fa-lg"></i> &nbsp; Zoeken</a>
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
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs pull-right" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link @if(Request::input('all') || Request::input('itemsPerPage') || !Request::input()) active @endif"
                                   id="alles-tab" data-toggle="tab" href="#alles" role="tab"
                                   aria-controls="alles" aria-selected="true">Alles <span class="badge badge-success">{{$booksAll->total()}}</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if(Request::input('inStock')) active @endif" id="opVoorraad-tab" data-toggle="tab" href="#opVoorraad"
                                   role="tab" aria-controls="opVoorraad" aria-selected="true">Op voorraad <span class="badge badge-success">{{$booksInStock->total()}}</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if(Request::input('sold')) active @endif" id="verkocht-tab" data-toggle="tab" href="#verkocht" role="tab"
                                   aria-controls="verkocht" aria-selected="false">Verkocht <span class="badge badge-success">{{$booksSold->total()}}</span></a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <!-- Alle boeken -->
                            <div class="tab-pane fade @if(Request::input('all') || Request::input('itemsPerPage') || !Request::input()) show active @endif"
                                 id="alles" role="tabpanel" aria-labelledby="alles-tab">
                                <form method="GET" action="{{ route('books.search') }}">
                                    <div class="input-group mb-3">
                                        <input required type="text" name="q" class="form-control"
                                               placeholder="Zoek op ID, titel, auteur of categorie">
                                        <div class="input-group-append">
                                            <button class="btn btn-success" type="submit">Zoek &nbsp; <i class="fas fa-search fa-lg"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <div class="row">
                                    <div class="col-md-6">
                                        {{ $booksAll->onEachSide(1)->links() }}
                                    </div>
                                    <div class="offset-md-2 col-md-4">
                                        <form method="GET" action="{{ route('books.index') }}">
                                            <select name="itemsPerPage" class="custom-select mb-3" onchange="this.form.submit()">
                                                <option selected>Boeken per pagina: {{Session::get('itemsPerPage')}}</option>
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                                <option value="250">250</option>
                                                <option value="500">500</option>
                                            </select>
                                        </form>
                                    </div>
                                </div>

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
                                        @foreach($booksAll as $book)
                                            <tr>
                                                <th scope="row" class="align-middle">{{$book->id}}</th>
                                                <td class="align-middle">{{$book->title}}</td>
                                                <td class="align-middle">{{$book->author}}</td>
                                                <td class="align-middle">
                                                    <a href="/categories/{{$book->category->id}}">{{$book->category->name}}</a>
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
                                <div class="row">
                                    <div class="col-2 mt-3">
                                        {{ $booksAll->onEachSide(1)->links() }}
                                    </div>
                                </div>
                            </div>

                            <!-- Boeken op voorraad -->
                            <div class="tab-pane fade @if(Request::input('inStock')) show active @endif"
                                 id="opVoorraad" role="tabpanel" aria-labelledby="opVoorraad-tab">
                                <form method="GET" action="{{ route('books.search') }}">
                                    <div class="input-group mb-3">
                                        <input required type="text" name="q" class="form-control"
                                               placeholder="Zoek op ID, titel, auteur of categorie">
                                        <div class="input-group-append">
                                            <button class="btn btn-success" type="submit">Zoek &nbsp; <i class="fas fa-search fa-lg"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <div class="row">
                                    <div class="col-md-6">
                                        {{ $booksInStock->onEachSide(1)->links() }}
                                    </div>
                                    <div class="offset-md-2 col-md-4">
                                        <form method="GET" action="{{ route('books.index') }}">
                                            <select name="itemsPerPage" class="custom-select mb-3" onchange="this.form.submit()">
                                                <option selected>Boeken per pagina: {{Session::get('itemsPerPage')}}</option>
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                                <option value="250">250</option>
                                                <option value="500">500</option>
                                            </select>
                                        </form>
                                    </div>
                                </div>

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
                                        @foreach($booksInStock as $book)
                                            <tr>
                                                <th scope="row" class="align-middle">{{$book->id}}</th>
                                                <td class="align-middle">{{$book->title}}</td>
                                                <td class="align-middle">{{$book->author}}</td>
                                                <td class="align-middle">
                                                    <a href="/categories/{{$book->category->id}}">{{$book->category->name}}</a>
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
                                <div class="row">
                                    <div class="col-2 mt-3">
                                        {{ $booksInStock->onEachSide(1)->links() }}
                                    </div>
                                </div>
                            </div>

                            <!-- Verkochte boeken -->
                            <div class="tab-pane fade @if(Request::input('sold'))) show active @endif"
                                 id="verkocht" role="tabpanel" aria-labelledby="verkocht-tab">
                                <form method="GET" action="{{ route('books.search') }}">
                                    <div class="input-group mb-3">
                                        <input required type="text" name="q" class="form-control"
                                               placeholder="Zoek op ID, titel, auteur of categorie">
                                        <div class="input-group-append">
                                            <button class="btn btn-success" type="submit">Zoek &nbsp; <i class="fas fa-search fa-lg"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <div class="row">
                                    <div class="col-md-6">
                                        {{ $booksSold->onEachSide(1)->links() }}
                                    </div>
                                    <div class="offset-md-2 col-md-4">
                                        <form method="GET" action="{{ route('books.index') }}">
                                            <select name="itemsPerPage" class="custom-select mb-3" onchange="this.form.submit()">
                                                <option selected>Boeken per pagina: {{Session::get('itemsPerPage')}}</option>
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                                <option value="250">250</option>
                                                <option value="500">500</option>
                                            </select>
                                        </form>
                                    </div>
                                </div>

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
                                        @foreach($booksSold as $book)
                                            <tr>
                                                <th scope="row" class="align-middle">{{$book->id}}</th>
                                                <td class="align-middle">{{$book->title}}</td>
                                                <td class="align-middle">{{$book->author}}</td>
                                                <td class="align-middle">
                                                    <a href="/categories/{{$book->category->id}}">{{$book->category->name}}</a>
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
                                <div class="row">
                                    <div class="col-2 mt-3">
                                        {{ $booksSold->onEachSide(1)->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
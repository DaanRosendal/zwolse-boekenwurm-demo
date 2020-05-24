@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <a href="{{route('books.index')}}" class="btn btn-success mb-3"><i class="fas fa-book fa-lg"></i> &nbsp; Boeken</a>
                <a href="{{route('categories.create')}}" class="btn btn-success mb-3 float-sm-right"><i class="fas fa-plus-square fa-lg"></i> &nbsp; Aanmaken</a>

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
                        Categorieën
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-sm text-left">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Categorie</th>
                                    <th scope="col">Boeken</th>
                                    <th scope="col" style="width: 150px">Acties</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                        <tr>
                                            <th scope="row" class="align-middle">{{$category->id}}</th>
                                            <td class="align-middle">{{$category->name}}</td>
                                            <td class="align-middle">{{$category->book_count}}</td>
                                            <td>
                                                <span class="list-group list-group-horizontal">
                                                    <a title="Boeken met categorie {{$category->name}} bekijken" href="{{route('categories.show', $category)}}" class="list-group-item list-group-item-action text-center p-1" style="width: 50px;">
                                                        <i class="fas fa-eye fa-lg ml-1 mr-1 text-success"></i></a>
                                                    <a title="Categorie aanpassen" href="{{route('categories.edit', $category)}}" class="list-group-item list-group-item-action text-center p-1" style="width: 50px;">
                                                        <i class="fas fa-edit fa-lg ml-1 mr-1 text-primary"></i></a>
                                                    <a title="Categorie verwijderen" href="{{route('categories.destroy', $category)}}" class="list-group-item list-group-item-action text-center p-1" style="width: 50px;"
                                                       onclick="return confirm('Weet je zeker dat je de categorie \'{{$category->name}}\' wilt verwijderen?')">
                                                        <i class="fas fa-trash-alt fa-lg text-danger ml-1 mr-1"></i>
                                                    </a>
                                                </span>
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
@endsection
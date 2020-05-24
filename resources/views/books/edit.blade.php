@extends("layouts.app")

@section('title')@parent | Boek Aanpassen @endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="{{route('books.index')}}" class="btn btn-success mb-3"><i class="fas fa-book fa-lg"></i></i> &nbsp; Boeken</a>
                <a href="{{route('books.search')}}" class="btn btn-success mb-3 float-sm-right"><i class="fas fa-search fa-lg"></i> &nbsp; Zoeken</a>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{session('success')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('anerror'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{session('anerror')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header text-left h3">
                        Boek <span class="text-muted">#{{$book->id}}</span> aanpassen
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/books/{{$book->id}}">
                            @csrf
                            @method('PUT')

                            <div class="form-group row">
                                <label for="title" id="titleLabel" class="col-md-4 col-form-label text-md-right">Titel *</label>

                                <div class="col-md-6">
                                    <input type="text" id="title" name="title"
                                           class="form-control @error('title') is-invalid @enderror"
                                           value="{{$book->title}}" autocomplete="off" required>
                                    @error('title')
                                    <span>
                                        <p class="text-danger mb-0" role="alert">{{ $message }}</p>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="author" id="authorLabel" class="col-md-4 col-form-label text-md-right">Auteur *</label>

                                <div class="col-md-6" id="">
                                    <input type="text" id="author" name="author"
                                           class="form-control @error('author') is-invalid @enderror"
                                           value="{{$book->author}}" required>
                                    @error('author')
                                    <span>
                                        <p class="text-danger mb-0" role="alert">{{ $message }}</p>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="category" class="col-md-4 col-form-label text-md-right">Categorie *</label>

                                <div class="col-md-6">
                                    <select id="category_id" type="text" class="form-control @error('category_id') is-invalid @enderror" name="category_id" required>
                                        <option value="">Selecteer</option>
                                        @foreach ($categories as $category)
                                            @if ($book->category_id == $category->id)
                                                <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                            @else
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
{{--                                    @foreach ($categories as $category)--}}
{{--                                        @if (old('category_id') == $category->id)--}}
{{--                                            <div class="custom-control custom-radio">--}}
{{--                                                <input type="radio" id="{{$category->id}}" name="category_id" class="custom-control-input" value="{{$category->id}}" checked>--}}
{{--                                                <label class="custom-control-label" for="{{$category->id}}">{{$category->name}}</label>--}}
{{--                                            </div>--}}
{{--                                        @else--}}
{{--                                            <div class="custom-control custom-radio">--}}
{{--                                                <input type="radio" id="{{$category->id}}" name="category_id" class="custom-control-input" value="{{$category->id}}">--}}
{{--                                                <label class="custom-control-label" for="{{$category->id}}">{{$category->name}}</label>--}}
{{--                                            </div>--}}
{{--                                        @endif--}}
{{--                                    @endforeach--}}
                                    @error('category')
                                    <span class="text-danger mb-0" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="location" id="locationLabel" class="col-md-4 col-form-label text-md-right">Locatie</label>

                                <div class="col-md-6" id="">
                                    <input type="text" id="location" name="location"
                                           class="form-control @error('location') is-invalid @enderror"
                                           value="{{$book->location}}">
                                    @error('location')
                                    <span>
                                            <p class="text-danger mb-0" role="alert">{{ $message }}</p>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="bol_link" id="bol_link" class="col-md-4 col-form-label text-md-right">Bol link</label>

                                <div class="col-md-6" id="">
                                    <input type="text" id="bol_link" name="bol_link"
                                           class="form-control @error('bol_link') is-invalid @enderror"
                                           value="{{$book->bol_link}}">
                                    @error('bol_link')
                                    <span>
                                            <p class="text-danger mb-0" role="alert">{{ $message }}</p>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="sold" id="sold" class="col-md-4 col-form-label text-md-right">Verkocht *</label>

                                <div class="col-md-6" id="">
                                    <input type="text" id="sold" name="sold"
                                           class="form-control @error('sold') is-invalid @enderror"
                                           value="{{ $book->sold ? 'ja' : 'nee' }}" required>
                                    @error('sold')
                                        <span>
                                            <p class="text-danger mb-0" role="alert">{{ $message }}</p>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-edit fa-lg"></i> &nbsp; Aanpassen
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
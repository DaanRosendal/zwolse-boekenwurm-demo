@extends("layouts.app")

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="{{route('categories.index')}}" class="btn btn-success mb-3"><i class="fas fa-list fa-lg"></i></i> &nbsp; Categorieën</a>
                <a href="{{route('books.index')}}" class="btn btn-success mb-3 float-sm-right"><i class="fas fa-book fa-lg"></i></i> &nbsp; Boeken</a>

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
                        Categorie aanmaken
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/categories">
                            @csrf

                            <div class="form-group row">
                                <label for="category" id="categoryLabel" class="col-md-4 col-form-label text-md-right">Categorienaam</label>

                                <div class="col-md-6">
                                    <input type="text" id="category" name="category"
                                           class="form-control @error('category') is-invalid @enderror"
                                           value="{{ old('category') }}" autocomplete="off" required>
                                    @error('category')
                                    <span>
                                        <p class="text-danger mb-0" role="alert">{{ $message }}</p>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-plus-square fa-lg"></i> &nbsp; Aanmaken
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
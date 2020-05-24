<?php

namespace App\Http\Controllers;

use App\Book;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Check of er een itemsPerPage request is
        if (request()->itemsPerPage) {
            Session::put('itemsPerPage', request()->itemsPerPage);
        // Check of de gebruiker nog niet eerder een itemsPerPage aangegeven heeft
        } elseif (! Session::get('itemsPerPage')){
            Session::put('itemsPerPage', 25);
        }

        // Pak itemsPerPage uit de session variabele
        $itemsPerPage = Session::get('itemsPerPage');

        // Alle boeken, boeken op voorraad en verkochte boeken ophalen
        $booksAll = Book::paginate($itemsPerPage, ['*'], 'all');
        $booksInStock = Book::where('sold', '=', false)->paginate($itemsPerPage, ['*'], 'inStock');
        $booksSold = Book::where('sold', '=', true)->paginate($itemsPerPage, ['*'], 'sold');

        return view('books.index', [
            'booksAll' => $booksAll,
            'booksInStock' => $booksInStock,
            'booksSold' => $booksSold,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::all();

        return view('books.create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        request()->validate([
            // Check of combinatie van titel en auteur al bestaat.
            // Het heeft me uren gekost om dit te vinden en ik snap nog steeds niet hoe het werkt, maar het werkt :)
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'int'],
            'location' => ['max:255'],
            'bol_link' => ['nullable', 'url', 'max:2000']
        ]);

        $author = request()->author;
        $title = request()->title;

        if(Book::where('author', $author)->where('title', $title)->count() >= 1){
            return redirect('books/create')->withAnerror(request()->title . ' van ' .request()->author. ' bestaat al!');
        }

        // Boek object aanmaken
        $book = new Book([
            'title' => request()->title,
            'author' => request()->author,
            'category_id' => request()->category_id,
            'location' => request()->location,
            'bol_link' => request()->bol_link,
            'sold' => 0
        ]);

        // Boek opslaan in de database
        try {
            $book->save();
        } catch (\Illuminate\Database\QueryException $e) {
            $request->flash();
            return redirect('books/create')
                ->withAnerror($e->getMessage());
        }
        return redirect(route('books.create'))
            ->withSuccess('Het boek "' . request()->title . '" van "' . request()->author . '" is aangemaakt!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Book $book)
    {
        $categories = Category::all();

        return view('books.edit', [
            'book' => $book,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Book $book
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Book $book)
    {
        request()->validate([
            // Check of combinatie van titel en auteur al bestaat.
            // Het heeft me uren gekost om dit te vinden en ik snap nog steeds niet hoe het werkt, maar het werkt :)
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'int'],
            'location' => ['max:255'],
            'bol_link' => ['nullable', 'url', 'max:2000'],
        ]);

        // check of de combinatie van titel en auteur al bestaat, exclusief het huidige boek
        $author = request()->author;
        $title = request()->title;

        if(Book::where('author', $author)->where('title', $title)->where('id', '!=', $book->id)->count() >= 1){
            return redirect('books/' . $book->id . '/edit')->withAnerror(request()->title . ' van ' .request()->author. ' bestaat al!');
        }

        // Valideer 'sold' apart, je kan request()->sold namelijk niet aanpassen
        $sold = strtolower(trim(request()->sold));

        $request2 = new Request([
            'sold' => $sold
        ]);

        $this->validate($request2, [
            'sold' => ['in:ja,nee', 'string', 'required']
        ]);

        if($sold == 'ja'){
            $sold = true;
        } elseif($sold == 'nee'){
            $sold = false;
        }

        // Aanpassingen in de database zetten
        try {
            $book->update([
                'title' => request()->title,
                'author' => request()->author,
                'category_id' => request()->category_id,
                'location' => request()->location,
                'bol_link' => request()->bol_link,
                'sold' => $sold
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            $request->flash();
            return redirect('books/' . $book->id . '/edit')
                ->withAnerror($e->getMessage());
        }

        return redirect('books/'.$book->id.'/edit')
            ->withSuccess('Het boek is aangepast');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Book $book
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->back()->withSuccess('Het boek "' . $book->title . '" van "'. $book->author .'" is verwijderd!');
    }

    /**
     * Search for the specified resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        $searchQuery = '%'.request()->q.'%';

        // Haal alle boeken op waar het id, de titel, de auteur of de categorie overeenkomt met de searchQuery
        $books = Book::join('categories', 'books.category_id', '=', 'categories.id')
            ->select('books.id as id', 'books.title as title', 'books.author as author',
                'categories.name as category', 'books.bol_link as bol_link', 'books.location as location',
                'books.sold as sold', 'categories.id as category_id')
            ->orWhere('books.id', '=', request()->q)
            ->orWhere('books.title', 'like', $searchQuery)
            ->orWhere('books.author', 'like', $searchQuery)
            ->orWhere('categories.name', 'like', $searchQuery)
            ->orderBy('id', 'asc')
            ->get();

        // Haal de count van de boeken op waar het id, de titel, de auteur of de categorie overeenkomt met de searchQuery
        $bookCount = Book::join('categories', 'books.category_id', '=', 'categories.id')
            ->select('books.id as id', 'books.title as title', 'books.author as author',
                'categories.name as category', 'books.bol_link as bol_link', 'books.location as location',
                'books.sold as sold', 'categories.id as category_id')
            ->orWhere('books.id', '=', request()->q)
            ->orWhere('books.title', 'like', $searchQuery)
            ->orWhere('books.author', 'like', $searchQuery)
            ->orWhere('categories.name', 'like', $searchQuery)
            ->orderBy('id', 'asc')
            ->count();

        // Stuur de vorige zoekopdracht mee met de view
        request()->flash();

        return view('books.search', [
            'books' => $books,
            'bookCount' => $bookCount
        ]);
    }

    /**
     * Mark the specified resource as sold.
     *
     * @param  \App\Book  $book
     */
    public function sold(Book $book){
        $book->sold = true;

        $book->save();

        return redirect()->back()->withSuccess('Het boek "' . $book->title . '" van "'. $book->author .'" is gemarkeerd als verkocht');
    }

    /**
     * Mark the specified resource as not sold.
     *
     * @param  \App\Book  $book
     */
    public function unsold(Book $book){
        $book->sold = false;

        $book->save();

        return redirect()->back()->withSuccess('Het boek "' . $book->title . '" van "'. $book->author .'" is gemarkeerd als NIET verkocht');
    }
}

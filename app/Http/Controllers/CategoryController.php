<?php

namespace App\Http\Controllers;

use App\Book;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //$categories = Category::orderBy('name', 'ASC')->get();
        $categories = Book::select(DB::raw('count(books.category_id) as book_count, categories.name as name, categories.id as id'))
            ->rightJoin('categories', 'books.category_id', '=', 'categories.id')
            ->groupBy('books.category_id', 'categories.name', 'categories.id')
            ->get();

        return view('categories.index', [
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'category' => 'required|unique:categories,name|string|max:255'
        ]);

        $category = new Category([
            'name' => request()->category
        ]);

        if($category->save()){
            return redirect(route('categories.index'))
                ->withSuccess('De categorie "' . request()->category . '"is aangemaakt!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Category $category)
    {
        $books = Book::where('category_id', '=', $category->id)->get();

        return view('categories.show', [
            'category' => $category,
            'books' => $books
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Category $category)
    {
        return view('categories.edit', [
            'category' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $oldCategory = $category->name;
        $newCategory = request()->category;

        request()->validate([
            'category' => 'required|string|max:255|unique:categories,name,'.$category->id
        ]);

        try {
            $category->update([
                'name' => request()->category
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            $request->flash();
            return redirect('category/' . $category->id . '/edit')
                ->withAnerror($e->getMessage());
        }

        $category->update();

        return redirect('categories/'.$category->id.'/edit')
            ->withSuccess("De categorie is aangepast van $oldCategory naar $newCategory");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $books = Book::where('category_id', '=', $category->id);

        if ($books->count() >= 1){
            return redirect()->route('categories.show', $category)
                ->withAnerror('Je kan deze categorie niet verwijderen omdat er boeken zijn die onder deze categorie vallen.');
        } else {
            $category->delete();
            return redirect()->route('categories.index')
                ->with(['success' => 'Categorie "'.$category->name.'" is verwijderd.']);
        }
    }
}

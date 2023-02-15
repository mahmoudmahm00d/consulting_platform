<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
        $this->middleware(['role:Admin']);
    }

    public function index()
    {
        $categories = Category::all();
        return view('categories.index', ['categories' => $categories]);
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => ['required', 'max:255', 'unique:categories'],
            'sequence' => 'numeric',
            'image' => ['nullable', 'max:255']
        ]);

        if ($request->hasFile('image')) {
            $fields['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($fields);

        return redirect('/categories')->with(['message' => 'Category Created Successfully!']);
    }

    public function show($id)
    {
        $category = Category::find($id);
        if (!$category) {
            throw new NotFoundHttpException();
        }

        return view('categories.show', ['category', $category]);
    }

    public function edit($id)
    {
        $category = Category::find($id);

        if (!$category) {
            throw new NotFoundHttpException();
        }

        return view('categories.edit', ['category' => $category]);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            throw new NotFoundHttpException();
        }

        $fields = $request->validate([
            'name' => ['required', 'max:255'],
            'sequence' => 'numeric',
        ]);

        if ($request->hasFile('image')) {
            $fields['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($fields);

        return redirect('/categories')->with(['message' => 'Category Updated Successfully!']);
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            throw new NotFoundHttpException();
        }

        if ($category->cover_image) {
            File::delete(storage_path('app/public/' . $category->image));
        }

        $category->delete();
        return redirect('/categories')->with(['message' => 'Category Deleted Successfully!']);
    }
}

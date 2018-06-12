<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Category;
use Validator;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Validation\Rule;
class CategoriesController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('owner:category', ['only' => ['update', 'show', 'destroy', 'edit']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $categories = Auth::user()->categories()->where('title', 'LIKE', "%$keyword%")
                ->orWhere('color', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $categories = Auth::user()->categories()->paginate($perPage);
        }

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255|unique:categories,title',
            'color' => 'required|size:7|unique:categories,color'
        ]);

        if ($validator->fails()) {
        	\Session::flash('warnning','Please enter the valid details');
            return Redirect::to('/categories')->withInput()->withErrors($validator);
        }
        $category = new Category;
        $category->user_id = Auth::id();
        $category->title = $request->title;
        $category->color = $request->color;
        $category->save();

        return redirect('categories')->with('flash_message', 'Category added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $category = Auth::user()->categories()->findOrFail($id);

        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $category = Auth::user()->categories()->findOrFail($id);

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {

        $requestData = $request->all();
        
        $category = Category::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => [
                'required',
                'max:255',
                Rule::unique('categories')->ignore($category->title, 'title')
            ],
            'color' => [
                'required',
                'max:7',
                Rule::unique('categories')->ignore($category->color, 'color')
            ],
        ]);

        if ($validator->fails()) {
        	\Session::flash('warnning','Please enter the valid details');
            return Redirect::to('/categories/'.$id.'/edit')->withInput()->withErrors($validator);
        }

        $category->update($requestData);

        return redirect('categories')->with('success', 'Category updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Category::destroy($id);

        return redirect('categories')->with('success', 'Category deleted!');
    }
}

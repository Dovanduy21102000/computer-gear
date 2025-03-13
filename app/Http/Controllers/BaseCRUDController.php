<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Database\Eloquent\Builder;

class BaseCRUDController extends Controller
{
    /**
     * @var Builder $model
     */
    public $pathView;
    protected $model;
    protected $fieldImage;
    public $folderImage;
    public $urlBase;
    public $titleIndex;
    public $titleCreate;
    public $titleEdit;

    public $columns = [];



    public function __construct()
    {
        $this->model = app()->make($this->model);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->model->paginate();
        return view($this->pathView . __FUNCTION__, compact('data'))
            ->with('title', $this->titleIndex)
            ->with('columns', $this->columns)
            ->with('urlBase', $this->urlBase);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view($this->pathView . 'add')
            ->with('urlBase', $this->urlBase)
            ->with('title', $this->titleCreate)
        ;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $model = $this->model::findOrFail($id);

        return view($this->pathView . __FUNCTION__, compact('model'));
    }

    /**
     * Show values
     */

    public function show($id)
    {
        $model = $this->model::findOrFail($id);

        return view($this->pathView . __FUNCTION__, compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validateStore($request);

        try {
            $model = new $this->model;

            $model->fill($request->except([$this->fieldImage]));

            if ($request->hasFile($this->fieldImage)) {
                $tmpPath = $request->file($this->fieldImage)->store($this->folderImage, 'public');
                $model->{$this->fieldImage} = $tmpPath; // No 'storage/' prefix needed
            }

            $model->save();

            return redirect()->route($this->urlBase . 'index')->with('success', 'Created successfully!');
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => $th->getMessage()]);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->validateUpdate($request, $id);

        try {
            $model = $this->model::findOrFail($id);

            $model->fill($request->except([$this->fieldImage]));

            if ($request->hasFile($this->fieldImage)) {
                $oldImage = Storage::put($this->folderImage, $request->{$this->fieldImage});

                $model->{$this->fieldImage} = 'storage/' . $oldImage;
            }

            $model->save();

            if ($request->hasFile($this->fieldImage)) {
                $oldImage = str_replace('storage/', '', $oldImage);

                Storage::delete($oldImage);
            }

            return redirect()->route($this->urlBase . 'index')->with('success', true);
        } catch (\Throwable $th) {
            return back()->with('success', false)->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $model = $this->model::findOrFail($id);

            $model->delete();

            if (Storage::exists($model->{$this->fieldImage})) {
                $image = str_replace('storage/', '', $model->{$this->fieldImage});

                Storage::delete($image);
            }

            return redirect()->route($this->urlBase . 'index')->with('success', true);
        } catch (\Throwable $th) {
            return back()->with('success', false)->with('error', $th->getMessage());
        }
    }

    /**
     * Validate request (to be overridden in child controllers).
     */
    protected function validateStore(Request $request) 
    {
        return $request->validate([]);
    }

    protected function validateUpdate(Request $request, $id) 
    {
        return $request->validate([]);
    }
}

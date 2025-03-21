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


    protected $searchable = [];


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

            $oldImagePath = $model->{$this->fieldImage}; // Store old image path

            $model->fill($request->except([$this->fieldImage]));

            if ($this->fieldImage && $request->hasFile($this->fieldImage)) {
                // Upload new image
                $newImagePath = Storage::put($this->folderImage, $request->{$this->fieldImage});
                $model->{$this->fieldImage} = $newImagePath;

            }

            $model->save();


            if ($this->fieldImage && $request->hasFile($this->fieldImage) && $oldImagePath) {
                // Delete old image if a new one was uploaded
                Storage::delete(str_replace('storage/', '', $oldImagePath));

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

            $model->delete(); // Soft delete

            return redirect()->route($this->urlBase . 'index')->with('success', 'Bản ghi đã chuyển vào thùng rác');
        } catch (\Throwable $th) {
            return back()->with('success', false)->with('error', $th->getMessage());
        }
    }

    /**
     * Restore a soft-deleted record.
     */
    public function restore($id)
    {
        try {
            $model = $this->model::withTrashed()->findOrFail($id);
            $model->restore(); // Restore soft-deleted record

            return redirect()->route($this->urlBase . 'index')->with('success', 'Record restored.');
        } catch (\Throwable $th) {
            return back()->with('success', false)->with('error', $th->getMessage());
        }
    }

    /**
     * Permanently delete a record.
     */
    public function forceDestroy($id)
    {
        try {
            $model = $this->model::withTrashed()->findOrFail($id);

            if ($this->fieldImage && Storage::disk('public')->exists($model->{$this->fieldImage})) {
                Storage::disk('public')->delete($model->{$this->fieldImage});
            }

            $model->forceDelete();

            return redirect()->route($this->urlBase . 'index')->with('success', 'Record permanently deleted.');

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

    public function search(Request $request)
    {
        $query = $request->input('query');
        $model = new $this->model;

        $searchableColumns = property_exists($this, 'searchable') ? $this->searchable : ['name'];

        // Perform search
        $data = $model::where(function ($q) use ($query, $searchableColumns) {
            foreach ($searchableColumns as $column) {
                $q->orWhere($column, 'LIKE', "%$query%");
            }
        });
        // ->paginate(10); // Paginate results

        return view($this->pathView . 'index', compact('data'))
            ->with('title', $this->titleIndex)
            ->with('columns', $this->columns)
            ->with('urlBase', $this->urlBase);
    }
}

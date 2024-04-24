<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\HasHelper;
use App\Traits\HasLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    use HasLog, HasHelper;

    public function index(Request $request)
    {
        $categories = Category::search($request
            ->input('search'))
            ->orderBy('id', 'asc')
            ->paginate(10);

        return response()->success(
            "Searching Categories Successful",
            CategoryResource::collection($categories)
        );
    }

    public function store(CategoryRequest $request)
    {
        $data = $request->toArray();

        $category = Category::create($data);

        return response()->success(
            'Storing Category Successful',
            new CategoryResource($category)
        );
    }

    public function show(Category $category)
    {
        return response()->success(
            "Searching Category Successful",
            new CategoryResource($category)
        );
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $changes = DB::transaction(function () use ($request, $category) {
            $changes = $this->resourceParser($request, $category);

            if ($changes) {
                $log = $this->log('UPDATE CATEGORY', $changes);
                $category->update([
                    'last_modified_log_id' => $log->id
                ]);
            }

            return $changes;
        });

        return response()->success(
            $changes
                ? 'Updating Category Successful'
                : 'No changes made.',
            new CategoryResource($category)
        );
    }

    public function destroy(Category $category)
    {
        DB::transaction(function () use ($category) {
            $log = $this->log('REMOVE CATEGORY', $category);
            $category->last_modified_log_id = $log->id;
            $category->save();
            $category->delete();
        });

        return response()->success(
            "Deleting Category Successful",
            new CategoryResource($category)
        );
    }

    public function trashed(Request $request)
    {
        $categories = Category::search($request
            ->input('search'))
            ->orderBy('id', 'asc')
            ->onlyTrashed()
            ->paginate(10);

        return response()->success(
            "Searching Deleted Category Successful",
            CategoryResource::collection($categories)
        );
    }

    public function restore(string $id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);

        DB::transaction(function () use ($category) {
            $log = $this->log(
                'RESTORE CATEGORY',
                $category
            );
            $category->last_modified_log_id = $log->id;
            $category->save();
            $category->restore();
        });

        return response()->success(
            "Restoring Category Successful",
            new CategoryResource($category)
        );
    }
}

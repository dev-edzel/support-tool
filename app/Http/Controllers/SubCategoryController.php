<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubCategoryRequest;
use App\Http\Resources\SubCategoryResource;
use App\Models\SubCategory;
use App\Traits\HasHelper;
use App\Traits\HasLogger;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubCategoryController extends Controller
{
    use HasLogger, HasHelper;

    public function index(Request $request)
    {
        $categories = SubCategory::search($request
            ->input('search'))
            ->orderBy('id', 'asc')
            ->paginate(10);

        return response()->success(
            'Searching Sub Categories Successful',
            SubCategoryResource::collection($categories)
        );
    }

    public function store(SubCategoryRequest $request)
    {
        $data = $request->validated();

        $subCategories = Collection::wrap($request
            ->input('type'))
            ->map(function ($type) use ($data) {
                return SubCategory::create(
                    array_merge(
                        $data,
                        ['type' => $type]
                    )
                );
            })->all();

        return response()->success(
            'Storing Sub Category Successful',
            SubCategoryResource::collection($subCategories)
        );
    }

    public function show(SubCategory $sub_category)
    {
        return response()->success(
            'Searching Sub Category Successful',
            new SubCategoryResource($sub_category)
        );
    }

    public function update(SubCategoryRequest $request, SubCategory $sub_category)
    {
        $changes = DB::transaction(function () use ($request, $sub_category) {
            $changes = $this->resourceParser($request, $sub_category);

            if ($changes) {
                $log = $this->log('UPDATE SUB CATEGORY', $changes);
                $sub_category->update([
                    'last_modified_log_id' => $log->id
                ]);
            }

            return $changes;
        });

        return response()->success(
            $changes
                ? 'Updating Sub Category Successful'
                : 'No changes made.',
            new SubCategoryResource($sub_category)
        );
    }

    public function destroy(SubCategory $sub_category)
    {
        DB::transaction(function () use ($sub_category) {
            $log = $this->log('REMOVE SUB CATEGORY', $sub_category);
            $sub_category->last_modified_log_id = $log->id;
            $sub_category->save();
            $sub_category->delete();
        });

        return response()->success(
            'Deleting Sub Category Successful',
            new SubCategoryResource($sub_category)
        );
    }

    public function trashed(Request $request)
    {
        $data = SubCategory::search($request
            ->input('search'))
            ->orderBy('id', 'asc')
            ->onlyTrashed()
            ->paginate(10);

        return response()->success(
            'Searching Deleted Sub Category Successful',
            SubCategoryResource::collection($data)
        );
    }

    public function restore(string $id)
    {
        $sub_category = SubCategory::onlyTrashed()->findOrFail($id);

        DB::transaction(function () use ($sub_category) {
            $log = $this->log(
                'RESTORE SUB CATEGORY',
                $sub_category
            );
            $sub_category->last_modified_log_id = $log->id;
            $sub_category->save();
            $sub_category->restore();
        });

        return response()->success(
            'Restoring Sub Category Successful',
            new SubCategoryResource($sub_category)
        );
    }
}

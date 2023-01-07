<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageUploadHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\QuickServiceStoreRequest;
use App\Models\Language;
use App\Models\QuickService;
use App\Models\QuickServiceTranslation;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class QuickServiceController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$quickServices = QuickService::listsTranslations('name')->select('quick_services.*');
			return Datatables::of($quickServices)
				->filter(function ($query) use ($request) {
					if (!empty($request->input('search'))) {
						$query->whereTranslationLike('name', "%" . $request->input('search') . "%");
						$query->orWhere('vehicle_types.id', 'LIKE', "%" . $request->input('search') . "%");
					}
				})
				->addColumn('image', function ($quickServices) {
					return "<img src='" . asset($quickServices->image) . "' style='width:100px' />";
				})
				->addColumn('action', function ($quickServices) {
					$edit_button = '<a href="' . route('admin.quick-service.edit', [$quickServices->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
					$delete_button = '<button data-id="' . $quickServices->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
					return '<div class="btn-icon-list">' . $edit_button . ' ' . $delete_button . '</div>';
				})
				->rawColumns(['action', 'image'])
				->make(true);
		}
		return view('admin.quickService.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function create()
	{
		$languages = Language::where('status', 'Active')->get();
		return view('admin.quickService.create', ['languages' => $languages]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store(QuickServiceStoreRequest $request)
	{
		$validated = $request->validated();
		$id = $request->input('edit_value');

		if ($id == 0) {

			$image = ImageUploadHelper::imageUpload($validated['image']);

			$insert_id = new QuickService();
			$insert_id->image = $image;
			$insert_id->save();

			$languages = Language::where('status', 'Active')->get();
			foreach ($languages as $language) {
				QuickServiceTranslation::create([
					'name'             => $request->input($language->language_code . '_name'),
					'quick_service_id' => $insert_id->id,
					'locale'           => $language->language_code,
				]);
			}
			return response()->json(['success' => true, 'message' => config('languageString.quick_service_added')]);
		} else {

			if ($request->file('image') != '') {
				$image = ImageUploadHelper::imageUpload($request->file('image'));

				$update = QuickService::find($id);
				$update->image = $image;
				$update->save();
			}

			$languages = Language::where('status', 'Active')->get();
			foreach ($languages as $language) {
				QuickServiceTranslation::updateOrCreate([
					'quick_service_id' => $id,
					'locale'           => $language->language_code,
				],
					[
						'quick_service_id' => $id,
						'locale'           => $language->language_code,
						'name'             => $request->input($language->language_code . '_name'),
					]);

			}
			return response()->json(['success' => true, 'message' => config('languageString.quick_service_updated')]);
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function edit($id)
	{
		$quickService = QuickService::find($id);
		if ($quickService) {
			$languages = Language::where('status', 'Active')->get();
			return view('admin.quickService.edit', ['quickService' => $quickService, 'languages' => $languages]);
		} else {
			abort(404);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function destroy($id)
	{
		QuickService::where('id', $id)->delete();
		return response()->json(['success' => true, 'message' => config('languageString.quick_service_deleted')]);
	}
}

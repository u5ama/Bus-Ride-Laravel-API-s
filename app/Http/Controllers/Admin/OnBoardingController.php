<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageUploadHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\OnBoardingStoreRequest;
use App\Models\Language;
use App\Models\OnBoarding;
use App\Models\OnBoardingTranslation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OnBoardingController extends Controller
{
	public function index(Request $request)
	{
		if ($request->ajax()) {
			//DB::enableQueryLog();
			$onBoardings = OnBoarding::listsTranslations('name')->select('on_boardings.*');
			// dd(DB::getQueryLog());
			return Datatables::of($onBoardings)
				->addColumn('action', function ($onBoardings) {
					$edit_button = '<a href="' . route('admin.on-boarding.edit', [$onBoardings->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
					return '<div class="btn-icon-list">' . $edit_button . '</div>';
				})
				->addColumn('icon', function ($onBoardings) {
					return '<img src="' . url($onBoardings->icon) . '" height="70px">';
				})
				->rawColumns(['action', 'icon'])
				->make(true);
		}
		return view('admin.onBoarding.index');
	}

	public function create()
	{
		$languages = Language::where('status', 'Active')->get();
		return view('admin.onBoarding.create', ['languages' => $languages]);
	}

	public function store(OnBoardingStoreRequest $request)
	{
		$validated = $request->validated();

		$id = $request->input('edit_value');

		if ($id == 0) {
			$icon = ImageUploadHelper::imageUpload($validated['icon']);
			$image = ImageUploadHelper::imageUpload($validated['image']);
            $order_by = OnBoarding::max('on_boarding_order_by');
			$insert_id = OnBoarding::create([
				'icon'  => $icon,
				'image' => $image,
                'on_boarding_order_by' => $order_by +1,
			]);
			$languages = Language::where('status', 'Active')->get();
			foreach ($languages as $language) {
				OnBoardingTranslation::create([
					'header_text'    => $request->input($language->language_code . '_header_text'),
					'description'    => $request->input($language->language_code . '_description'),
					'on_boarding_id' => $insert_id->id,
					'locale'         => $language->language_code,
				]);
			}
			return response()->json(['success' => true, 'message' => config('languageString.on_boarding_added')]);
		} else {

			if ($request->file('icon') != '') {
				$icon = ImageUploadHelper::imageUpload($request->file('icon'));

				$update = OnBoarding::find($id);
				$update->icon = $icon;
				$update->save();
			}

			if ($request->file('image') != '') {
				$image = ImageUploadHelper::imageUpload($request->file('image'));

				$update = OnBoarding::find($id);
				$update->image = $image;
				$update->save();
			}


			$languages = Language::where('status', 'Active')->get();
			foreach ($languages as $language) {
				OnBoardingTranslation::updateOrCreate([
					'on_boarding_id' => $id,
					'locale'         => $language->language_code,
				],
					[
						'on_boarding_id' => $id,
						'locale'         => $language->language_code,
						'header_text'    => $request->input($language->language_code . '_header_text'),
						'description'    => $request->input($language->language_code . '_description'),

					]);

			}
			return response()->json(['success' => true, 'message' => config('languageString.on_boarding_updated')]);
		}
	}

	public function edit($id)
	{
		$onBoarding = OnBoarding::find($id);
		if ($onBoarding) {
			$languages = Language::where('status', 'Active')->get();
			return view('admin.onBoarding.edit', [
				'onBoarding' => $onBoarding,
				'languages'  => $languages
			]);
		} else {
			abort(404);
		}
	}

	public function destroy($id): \Illuminate\Http\JsonResponse
    {
		Fuel::where('id', $id)->delete();
		return response()->json(['success' => true, 'message' => config('languageString.fuel_deleted')]);
	}

	public function orderBy(Request $request)
    {
        $order_by = OnBoarding::all();
        return view('admin.onBoarding.orderBy', ['order_by' => $order_by]);
    }

    public function saveOrder(Request $request): \Illuminate\Http\JsonResponse
    {
        $selectedOrder = $request->input('selectedOrder');
         foreach($selectedOrder as $key => $value) {
             $update = ['on_boarding_order_by' => $key];
             OnBoarding::where('id', $value)->update($update);
         }
         return response()->json([
             'message' => trans('languageString.onBoarding_ordered'),
         ]);
    }

}

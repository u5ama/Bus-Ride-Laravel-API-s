<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageUploadHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\NotificationStoreRequest;
use App\Models\Device;
use App\Models\Language;
use App\Models\Notification;
use App\Models\NotificationTranslation;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NotificationController extends Controller
{

    public function index(Request $request)
    {
        if($request->ajax()){
            $notifications = Notification::select("*");
            return Datatables::of($notifications)
                ->addCOlumn('action', function ($notifications) {
                    $details_button =  '<div class="row"> <a href="' . route('admin.details', [$notifications->id]) . '"><button class="detail-single btn btn-icon btn-primary waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-bullseye font-size-16 align-middle"></i></button></a>';
                    $delete_button = '<button data-id="' . $notifications->id . '" class="delete-single btn btn-icon btn-danger waves-effect waves-light ml-2" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button></div>';
                    return $details_button . '' . $delete_button;
                })
                ->addColumn('date', function($notifications){
                    return $notifications->created_at->diffForHumans();
                })
                ->addColumn('message', function($notifications){
                    return $notifications->message;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.notification.index');
    }

    public function create()
    {
        $languages = Language::where('status', 'Active')->get();
        return view('admin.notification.create', ['languages' => $languages]);
    }

    public function store(NotificationStoreRequest $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validated();
        $id = $request->input('edit_value');

        $image = '';
        $description = '';
        if ($request->hasFile('image')) {
            $image = ImageUploadHelper::imageUpload($request['image']);
        }
        $languages = Language::where('status', 'Active')->get();

        if ($id == null) {
            $insert_id = new Notification();
            $insert_id->image = $image;
            $insert_id->save();
        }

        foreach ($languages as $language) {
            NotificationTranslation::create([
                'notification_id' => $insert_id->id,
                'title' => $request->input($language->language_code . '_title'),
                'message' => $request->input($language->language_code . '_message'),
                'description' => $request->input($language->language_code . '_description'),
                'locale' => $language->language_code,
            ]);
        }
        return response()->json(['message' => config('languageString.notification_sent')], 200);
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        Notification::where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => config('languageString.notification_deleted')]);
    }

    public function details($id)
    {
        $notifications = Notification::find($id);
        $notification_translation = NotificationTranslation::find($id);
        return view('admin.notification.show', ['notifications' => $notifications, 'notification_translation' => $notification_translation]);
    }

}

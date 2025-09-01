<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    /**
     * Menampilkan semua pengaturan.
     */
    public function index()
    {
        // Mengambil semua settings dan mengubahnya menjadi format key => value
        $settings = Setting::all()->pluck('value', 'key');
        return response()->json($settings);
    }

    /**
     * Menyimpan atau memperbarui pengaturan.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings' => ['required', 'array']
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        foreach ($request->settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return response()->json(['message' => 'Settings saved successfully']);
    }
}
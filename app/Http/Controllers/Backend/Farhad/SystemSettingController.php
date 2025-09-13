<?php

namespace App\Http\Controllers\Backend\Farhad;

use Exception;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class SystemSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:system-settings_edit')->only(['edit']);
        $this->middleware('can:system-settings_update')->only(['update']);
    }


    public function edit()
    {
        $setting = SystemSetting::first();
        return view('backend.layouts.system-setting.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'system_title' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'tag_line' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'copyright_text' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:5120',
            'mini_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:5120',
            'favicon' => 'nullable|image|mimes:png,ico|max:5120',
        ]);

        // $setting = SystemSetting::firstOrNew(['id' => 1]); //do not use mass assignment
        // $setting = SystemSetting::firstOrCreate(['id' => 1]);
        // $setting = SystemSetting::updateOrCreate(['id' => 1]);

        // Always fetch the first record (id = 1)
        // $setting = SystemSetting::findOrFail(1); //if use seeder

        $setting = SystemSetting::first();
        if (!$setting) {
            $setting = new SystemSetting();
            $setting->id = 1; // manually assign
        }

        $directory = 'uploads/system-settings-images/';

        // Ensure directory exists
        if (!File::exists(public_path($directory))) {
            File::makeDirectory(public_path($directory), 0775, true);
        }

        // Logo
        if ($request->file('logo')) {
            if ($setting->logo && file_exists(public_path($setting->logo))) {
                unlink(public_path($setting->logo));
            }
            $logo = $request->file('logo');
            $logoName = 'logo_' . time() . '_' . uniqid() . '.' . $logo->getClientOriginalExtension();
            $resizedLogo = Image::make($logo)->resize(187, 32);
            $resizedLogo->save(public_path($directory . $logoName));
            $setting->logo = $directory . $logoName;
        }

        // Mini Logo
        if ($request->file('mini_logo')) {
            if ($setting->mini_logo && file_exists(public_path($setting->mini_logo))) {
                unlink(public_path($setting->mini_logo));
            }
            $miniLogo = $request->file('mini_logo');
            $miniLogoName = 'mini_logo_' . time() . '_' . uniqid() . '.' . $miniLogo->getClientOriginalExtension();
            $resizedMiniLogo = Image::make($miniLogo)->resize(80, 80);
            $resizedMiniLogo->save(public_path($directory . $miniLogoName));
            $setting->mini_logo = $directory . $miniLogoName;
        }

        // Favicon
        if ($request->file('favicon')) {
            if ($setting->favicon && file_exists(public_path($setting->favicon))) {
                unlink(public_path($setting->favicon));
            }
            $favicon = $request->file('favicon');
            $faviconName = 'favicon_' . time() . '_' . uniqid() . '.' . $favicon->getClientOriginalExtension();
            $resizedFavicon = Image::make($favicon)->resize(128, 128);
            $resizedFavicon->save(public_path($directory . $faviconName));
            $setting->favicon = $directory . $faviconName;
        }

        // Other fields
        $setting->system_title = $request->system_title;
        $setting->company_name = $request->company_name;
        $setting->tag_line = $request->tag_line;
        $setting->phone_number = $request->phone_number;
        $setting->whatsapp_number = $request->whatsapp_number;
        $setting->email = $request->email;
        $setting->copyright_text = $request->copyright_text;

        $setting->save();

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }

    public function editMailSettings()
    {
        return view('backend.layouts.mail-setting.edit');
    }

    public function updateMailSettings(Request $request)
    {
        $request->validate([
            'mail_mailer' => 'required|string|max:255',
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|string',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|string|max:50',
            'mail_from_address' => 'required|email|max:255',
        ]);

        try {
            $envContent = File::get(base_path('.env'));
            $lineBreak = "\n";
            $envContent = preg_replace([
                '/MAIL_MAILER=(.*)\s/',
                '/MAIL_HOST=(.*)\s/',
                '/MAIL_PORT=(.*)\s/',
                '/MAIL_USERNAME=(.*)\s/',
                '/MAIL_PASSWORD=(.*)\s/',
                '/MAIL_ENCRYPTION=(.*)\s/',
                '/MAIL_FROM_ADDRESS=(.*)\s/',
            ], [
                'MAIL_MAILER=' . $request->mail_mailer . $lineBreak,
                'MAIL_HOST=' . $request->mail_host . $lineBreak,
                'MAIL_PORT=' . $request->mail_port . $lineBreak,
                'MAIL_USERNAME=' . $request->mail_username . $lineBreak,
                'MAIL_PASSWORD=' . $request->mail_password . $lineBreak,
                'MAIL_ENCRYPTION=' . $request->mail_encryption . $lineBreak,
                'MAIL_FROM_ADDRESS=' . '"' . $request->mail_from_address . '"' . $lineBreak,
            ], $envContent);

            if ($envContent !== null) {
                File::put(base_path('.env'), $envContent);
            }
            return back()->with('success', 'Updated successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to update');
        }

        return redirect()->back();
    }
}

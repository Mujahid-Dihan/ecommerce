<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function general()
    {
        $settings = Setting::where('group', 'general')->pluck('value', 'key');
        return Inertia::render('Admin/Settings/General', ['settings' => $settings]);
    }

    public function updateGeneral(Request $request)
    {
        $fields = ['site_name', 'site_tagline', 'contact_email', 'contact_phone', 'address', 'footer_text'];
        foreach ($fields as $key) {
            if ($request->has($key)) {
                Setting::setValue($key, $request->input($key), 'general');
            }
        }
        return redirect()->back()->with('success', 'Settings saved.');
    }

    public function payment()
    {
        $settings = Setting::where('group', 'payment')->pluck('value', 'key');
        return Inertia::render('Admin/Settings/Payment', ['settings' => $settings]);
    }

    public function updatePayment(Request $request)
    {
        $fields = ['paypal_enabled', 'paypal_client_id', 'paypal_secret', 'stripe_enabled', 'stripe_key', 'stripe_secret', 'cod_enabled', 'bkash_enabled', 'bkash_app_key', 'bkash_app_secret'];
        foreach ($fields as $key) {
            if ($request->has($key)) {
                Setting::setValue($key, $request->input($key), 'payment');
            }
        }
        return redirect()->back()->with('success', 'Payment settings saved.');
    }

    public function smtp()
    {
        $settings = Setting::where('group', 'smtp')->pluck('value', 'key');
        return Inertia::render('Admin/Settings/SMTP', ['settings' => $settings]);
    }

    public function updateSmtp(Request $request)
    {
        $fields = ['mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 'mail_from_address', 'mail_from_name'];
        foreach ($fields as $key) {
            if ($request->has($key)) {
                Setting::setValue($key, $request->input($key), 'smtp');
            }
        }
        return redirect()->back()->with('success', 'SMTP settings saved.');
    }

    public function features()
    {
        $settings = Setting::where('group', 'features')->pluck('value', 'key');
        return Inertia::render('Admin/Settings/Features', ['settings' => $settings]);
    }

    public function updateFeatures(Request $request)
    {
        $fields = ['pos_enabled', 'wallet_enabled', 'affiliate_enabled', 'wholesale_enabled', 'auction_enabled', 'classified_enabled', 'blog_enabled', 'newsletter_enabled', 'refund_enabled', 'flash_deals_enabled'];
        foreach ($fields as $key) {
            Setting::setValue($key, $request->boolean($key) ? '1' : '0', 'features');
        }
        return redirect()->back()->with('success', 'Feature settings saved.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Accepts JSON: { name, email, message }
     * Returns JSON: { message: "..." }
     */
    public function storeApi(Request $request)
    {
        // Validate (localized messages come from resources/lang/*/validation.php)
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:120'],
            'email'   => ['required', 'email', 'max:160'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        // (Optional) Save to DB if you have a model:
        // \App\Models\ContactMessage::create($data);

        // Send mail (simple + robust)
        try {
            $to = "info@emiratikhanjer.com";

            Mail::send([], [], function ($m) use ($to, $data) {
                $m->to($to)
                  ->subject('New Contact Message')
                  ->setBody(
                      view('emails.contact', ['d' => $data])->render(),
                      'text/html'
                  );
            });
        } catch (\Throwable $e) {
            Log::error('Contact mail failed', ['e' => $e->getMessage()]);
            return response()->json([
                'message' => __('home.toast_error'),
            ], 500);
        }

        return response()->json([
            'message' => __('home.toast_success'),
        ]);
    }
}

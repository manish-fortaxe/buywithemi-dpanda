<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    function shiprocketWebhook(Request $request)
    {
        Log::info('shiprocketWebhook - '.json_encode($request->all()));

        return response()->json('done', 200);
    }
}

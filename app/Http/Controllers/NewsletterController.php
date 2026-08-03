<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsletterRequest;
use App\Models\NewsletterSubscriber;

class NewsletterController extends Controller
{
    public function subscribe(StoreNewsletterRequest $request)
    {
        NewsletterSubscriber::updateOrCreate(
            ['email' => $request->input('email')],
            ['subscribed' => true]
        );

        return response()->json(['message' => 'Subscribed successfully.'], 201);
    }
}

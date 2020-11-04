<?php

namespace App\Http\Controllers;

use App\Models\AccessRequest;

class PageController extends Controller
{
    /**
     * Welcome page
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * View for displaying access requests
     */
    public function accessRequests()
    {
        $requests = AccessRequest::all();

        return view('access-requests', compact('requests'));
    }
}

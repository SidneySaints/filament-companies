<?php

namespace Wallo\FilamentCompanies\Http\Controllers\Livewire;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UserProfileController extends Controller
{
    /**
     * Show the user profile screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        return view('filament.pages.profile', [
            'request' => $request,
            'user' => $request->user(),
        ]);
    }
}

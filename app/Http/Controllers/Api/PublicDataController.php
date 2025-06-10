<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Federation;
use Illuminate\Http\Request;

class PublicDataController extends Controller
{
    public function index()
    {
        $data = Federation::with([
            'entities.teams.athletes.transfers',
            'entities.teams.athletes.performances',
            'entities.teams.user',
        ])->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}

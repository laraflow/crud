<?php

namespace Laraflow\Crud\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Laraflow\Crud\Http\Requests\CrudGenerateRequest;

class CrudGenerateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('crud::generate');
    }

    public function attempt(CrudGenerateRequest $request): array
    {
        return $request->all();
    }
}

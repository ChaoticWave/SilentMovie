<?php namespace ChaoticWave\SilentMovie\Http\Controllers;

use ChaoticWave\BlueVelvet\Providers\BaseServiceProvider;
use ChaoticWave\BlueVelvet\Traits\Curly;
use ChaoticWave\SilentMovie\Facades\ImdbApi;
use ChaoticWave\SilentMovie\Http\Controllers\Controller;
use ChaoticWave\SilentMovie\Managers\ApiManager;
use ChaoticWave\SilentMovie\Services\OmdbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ImdbController extends Controller
{
    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        $_result = ImdbApi::searchPeople($request->get('search-person'));

        return view('home',
            [
                'search'       => $_result,
                'searchText'   => var_export($_result, true),
                'totalResults' => data_get($_result, 'totalResults'),
            ]);
    }
}

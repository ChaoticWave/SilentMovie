<?php namespace ChaoticWave\SilentMovie\Http\Controllers;

use ChaoticWave\SilentMovie\Facades\ImdbApi;
use Illuminate\Http\Request;

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
        $_result = ImdbApi::searchPeople($_query = $request->get('search-person'));

        return view('search',
            [
                'search'       => $_result->mappedArray(),
                'searchQuery'  => $_query,
                'searchText'   => var_export($_result, true),
                'totalResults' => data_get($_result, 'totalResults'),
            ]);
    }
}

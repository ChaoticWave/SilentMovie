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

        $_exact = [];
        foreach (array_get($_mapped = $_result->mappedArray(), 'exact', []) as $_entity) {
            $_exact[] = $_entity['name'] . ' (' . $_entity['id'] . ')';
        }

        return view('search',
            [
                'exact'        => $_exact,
                'search'       => $_mapped,
                'searchQuery'  => $_query,
                'searchText'   => var_export($_result, true),
                'totalResults' => data_get($_result, 'totalResults'),
            ]);
    }
}

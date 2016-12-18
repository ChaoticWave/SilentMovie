<?php namespace ChaoticWave\SilentMovie\Http\Controllers;

use ChaoticWave\BlueVelvet\Providers\BaseServiceProvider;
use ChaoticWave\BlueVelvet\Traits\Curly;
use ChaoticWave\SilentMovie\Http\Controllers\Controller;
use ChaoticWave\SilentMovie\Services\OmdbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ImdbController extends Controller
{
    //******************************************************************************
    //* Traits
    //******************************************************************************

    use Curly;

    //******************************************************************************
    //* Constants
    //******************************************************************************

    /**
     * @var string
     */
    const API_TITLE_ENDPOINT = 'http://www.imdb.com/xml/find?json=1&tt=on';
    /**
     * @var string
     */
    const API_PERSON_ENDPOINT = 'http://www.imdb.com/xml/find?json=1&nm=on';

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
        $_term = $request->get('search-person');
        $_result = $this->httpGet(static::API_PERSON_ENDPOINT, ['q' => $_term]);
        is_string($_result) && $_result = json_decode($_result, true);
        $this->addPerson($_result);

        return view('home',
            [
                'search'       => $_result,
                'searchText'   => var_export($_result, true),
                'totalResults' => data_get($_result, 'totalResults'),
            ]);
    }

    protected function addPerson($person)
    {
        $_approx = array_get($person, 'name_approx');
        $_popular = array_get($person, 'name_popular');
        $_exact = array_get($person, 'name_exact');
        $_substring = array_get($person, 'name_substring');
        //  Push to ES
    }
}

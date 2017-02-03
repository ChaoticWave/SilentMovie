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
        $_exact = $_mapped = null;

        if (!empty($_result = ImdbApi::searchPeople($_query = $request->get('search-person')))) {
            $_mapped = $_result->mappedArray();

            foreach ($_mapped as $_type => $_entities) {
                foreach ($_entities ?: [] as $_id => $_entity) {
                    $_mapped[$_type][$_id]['name'] = $this->highlight($_entity['name'], $_query);
                }
            }
        }

        return view('search',
            [
                'search'       => $_mapped,
                'searchQuery'  => $_query,
                'searchText'   => var_export($_result, true),
                'totalResults' => data_get($_result, 'totalResults'),
            ]);
    }

    /**
     * @param $haystack
     * @param $needle
     *
     * @return string
     */
    protected function highlight($haystack, $needle)
    {
        $_start = stripos($haystack, $needle);

        if (false !== $_start) {
            $_len = strlen($needle);
            $_end = $_start + $_len;

            return substr($haystack, 0, $_start) . '<span class="highlighted">' . substr($haystack, $_start, $_len) . '</span>' . substr($haystack, $_end);
        }

        return $haystack;
    }
}

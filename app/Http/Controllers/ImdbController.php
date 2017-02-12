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
    public function peopleSearch(Request $request)
    {
        $_mapped = null;

        if (!empty($_result = ImdbApi::searchPeople($_query = $request->get('search-person')))) {
            $_mapped = $_result->mappedArray();

            foreach ($_mapped as $_type => &$_entities) {
                foreach ($_entities ?: [] as $_id => &$_entity) {
                    $_entity['name'] = $this->highlight($_entity['name'], $_query);;
                    $_entity['link'] = 'http://www.imdb.com/name/' . $_id;
                    $_entity['icon'] = empty($_entity['ingested_at']) ? 'fa-user-o ingest' : 'fa-user-circle ingested';
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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function titleSearch(Request $request)
    {
        $_mapped = null;

        if (!empty($_result = ImdbApi::searchTitle($_query = $request->get('search-title')))) {
            $_mapped = $_result->mappedArray();

            foreach ($_mapped as $_type => $_entities) {
                foreach ($_entities ?: [] as $_id => $_entity) {
                    $_mapped[$_type][$_id]['title'] = $this->highlight($_entity['title'], $_query);
                    $_mapped[$_type][$_id]['link'] = 'http://www.imdb.com/title/' . $_id;
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

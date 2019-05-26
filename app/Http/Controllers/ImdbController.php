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
        $mapped = null;

        if (!empty($result = ImdbApi::searchPeople($query = $request->get('search-person')))) {
            $mapped = $result->mappedArray();

            foreach ($mapped as $type => &$entities) {
                foreach ($entities ?: [] as $id => &$entity) {
                    $entity['name'] = $this->highlight($entity['name'], $query);;
                    $entity['link'] = 'http://www.imdb.com/name/' . $id;
                    $entity['icon'] = empty($entity['ingested_at']) ? 'fa-user-o ingest' : 'fa-user-circle ingested';
                }
            }
        }

        return view('search',
            [
                'search'       => $mapped,
                'searchQuery'  => $query,
                'searchText'   => var_export($result, true),
                'totalResults' => data_get($result, 'totalResults'),
            ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function titleSearch(Request $request)
    {
        $mapped = null;

        if (!empty($result = ImdbApi::searchTitle($query = $request->get('search-title')))) {
            $mapped = $result->mappedArray();

            foreach ($mapped as $type => $entities) {
                foreach ($entities ?: [] as $id => $entity) {
                    $mapped[$type][$id]['title'] = $this->highlight($entity['title'], $query);
                    $mapped[$type][$id]['link'] = 'http://www.imdb.com/title/' . $id;
                }
            }
        }

        return view('search',
            [
                'search'       => $mapped,
                'searchQuery'  => $query,
                'searchText'   => var_export($result, true),
                'totalResults' => data_get($result, 'totalResults'),
            ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        $mapped = null;

        if (!empty($result = ImdbApi::search($query = $request->get('search-search')))) {
            $mapped = $result->mappedArray();

            foreach ($mapped as $type => $entities) {
                foreach ($entities ?: [] as $id => $entity) {
                    $mapped[$type][$id]['title'] = $this->highlight($entity['title'], $query);
                    $mapped[$type][$id]['link'] = 'http://www.imdb.com/title/' . $id;
                }
            }
        }

        return view('search',
            [
                'search'       => $mapped,
                'searchQuery'  => $query,
                'searchText'   => var_export($result, true),
                'totalResults' => data_get($result, 'totalResults'),
            ]
        );
    }

    /**
     * @param $haystack
     * @param $needle
     *
     * @return string
     */
    protected function highlight($haystack, $needle)
    {
        $start = stripos($haystack, $needle);

        if (false !== $start) {
            $len = strlen($needle);
            $end = $start + $len;

            return substr($haystack, 0, $start) .
                '<span class="highlighted">' .
                substr($haystack, $start, $len) .
                '</span>' .
                substr($haystack, $end);
        }

        return $haystack;
    }
}

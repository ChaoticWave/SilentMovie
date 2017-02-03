<div id="search-results-{{ $_type }}" class="panel panel-default simo-results-panel">
    <div class="panel-heading" style="text-transform: capitalize"><h3>{{ $_type }} Matches</h3></div>
    <ul class="list-group">
        @foreach($_typeList as $_item)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-1 simo-icon"><i class="fa fa-fw fa-user-o"></i></div>
                    <div class="col-md-3 simo-title"><a href="{{ $_item['link'] }}"
                                                        target="_blank"
                                                        class="simo-link">{!! array_get($_item, 'title') ?: array_get($_item, 'name') !!}</a></div>
                    @if (!empty($_item['title']))
                        <div class="col-md-3 simo-info">{!! $_item['title'] !!}</div>
                        <div class="col-md-3 simo-info">{!! array_get($_item, 'description', '[no description]') !!}</div>
                    @else
                        <div class="col-md-6 simo-info">{!! array_get($_item, 'description', '[no description]') !!}</div>
                    @endif
                </div>
            </li>
        @endforeach
    </ul>
</div>

<div id="search-results-{{ $_type }}" class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title">{{ $_type }} Matches</h3></div>
    <div class="panel-body">
        <div class="form-horizontal">
            @foreach($_typeList as $_item)
                <div class="form-group">
                    <label class="col-sm-3 control-label">ID</label>
                    <div class="col-sm-3">
                        <p class="form-control-static">{{ array_get($_item,'id') }}</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Name</label>
                    <div class="col-sm-3">
                        <p class="form-control-static">{{ array_get($_item,'name') }}</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Description</label>
                    <div class="col-sm-3">
                        <p class="form-control-static">{{ array_get($_item,'description') }}</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Title</label>
                    <div class="col-sm-3">
                        <p class="form-control-static">{{ array_get($_item,'title') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

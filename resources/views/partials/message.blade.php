
@if(session()->has('success'))
    <div class="alert alert-success">
        {!! session()->get('success') !!}
        <a class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
    </div>
@elseif(session()->has('warning'))
    <div class="alert alert-warning">
        {!! session()->get('warning') !!}
        <a class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
    </div>
@elseif($errors->any())
    <div class="alert alert-danger">
        {!! $errors->first() !!}
        <a class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
    </div>
@endif

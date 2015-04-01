<!-- Panel start -->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Required Info</h3>
	</div>
	<div class="panel-body">

		@include('admin.form-errors')

		<div class="form-group @if($errors->has('title')) has-error @endif">
			{!! Form::label('title', 'Title', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-3">
                {!! Form::text('title', null, ['class' => 'form-control slug-target']) !!}
            </div>
            <div class="col-sm-3">
                {!! Form::text('slug', null, ['class' => 'form-control txt-slug', 'placeholder' => 'URL slug']) !!}
            </div>
        </div>

        <div class="form-group @if($errors->has('description')) has-error @endif">
			{!! Form::label('description', 'Description', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
            </div>
        </div>
		<div class="form-group @if($errors->has('published_at')) has-error @endif">
			{!! Form::label('published_at', 'Publish Date', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-3">
                <div class="input-group date">
                  {!! Form::text('published_at', null, ['class' => 'form-control']) !!}
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>  
            </div>
        </div>      
	</div>
</div>
<!-- Panel end -->


<!-- Panel start -->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Addtional Info</h3>
	</div>
	<div class="panel-body">					
		<div class="form-group">
			{!! Form::label('excerpt', 'Excerpt', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::textarea('excerpt', null, ['class' => 'form-control', 'rows' => 3]) !!}
            </div>
        </div>
        <div class="form-group">
			{!! Form::label('image', 'Image', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('image', null, ['class' => 'form-control']) !!}
            </div>
        </div>
	</div>
</div>
<!-- Panel end -->

<div class="row">
    <div class="col-sm-1  col-sm-push-5">
        <a href="{{ route('admin.news.list') }}">
            <button type="button" class="btn btn-default btn-trans btn-full-width" data-toggle="tooltip" data-placement="top" title="Back to news list">
                <i class="fa fa-mail-reply"></i> &nbsp; News List
            </button>
        </a>
    </div>
    <div class="col-sm-1 col-sm-push-5">
        {!! Form::submit('Save news', ['class' => 'btn btn-primary btn-trans form-control']) !!}
    </div>
</div>
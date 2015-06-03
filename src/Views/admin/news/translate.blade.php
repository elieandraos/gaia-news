@extends('admin.layout')

@section('content')

<div class="row">
	<div class="col-md-12">
		<!-- Breadcrumb -->
		<ul class="breadcrumb">
		    <li><a href="#">Dashboard</a></li>
		    <li>News</li>
		    <li class="active">Translate</li>
		</ul>

		<h1 class="h1">Translate News</h1>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		{!! Form::model( $news, ['route' => ['admin.news.translate-store', $news->id, $locale], 'class' => 'form-horizontal', 'role' => 'form']) !!}
			@include('admin.news._form_translate', ['locale' => $locale])
		{!! Form::close() !!}
	</div>
</div>

@stop
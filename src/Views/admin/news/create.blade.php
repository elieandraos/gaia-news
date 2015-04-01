@extends('admin.layout')

@section('content')

<div class="row">
	<div class="col-md-12">
		<!-- Breadcrumb -->
		<ul class="breadcrumb">
		    <li><a href="#">Dashboard</a></li>
		    <li>News</li>
		    <li class="active">Create</li>
		</ul>

		<h1 class="h1">Create News</h1>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		{!! Form::open(['route' => 'admin.news.store', 'class' => 'form-horizontal', 'role' => 'form']) !!}
			@include('admin.news._form')
		{!! Form::close() !!}
	</div>
</div>

@stop
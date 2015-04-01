@extends('admin.layout')

@section('content')

<div class="row">
	<div class="col-md-12">
		<!-- Breadcrumb -->
		<ul class="breadcrumb">
		    <li><a href="#">Dashboard</a></li>
		    <li>News</li>
		    <li class="active">List</li>
		</ul>


		<!-- Panel start -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
				News List
				<a href="{{ route('admin.news.create') }}" class="pull-right">
					<button type="button" class="btn btn-primary btn-trans btn-xs " data-toggle="tooltip" data-placement="top" title="Add a new news">
						<i class="fa fa-plus-square-o"></i> &nbsp; Create New
					</button>
				</a>
				</h3>
			</div>
			<div class="panel-body">
				<!-- News List -->
				<table class="table table-hover">
				  <thead>
				    <tr>
				      <th>Title</th>
				      <th>Publish Date</th>
				      <th>Action</th>
				    </tr>
				  </thead>
				  <tbody>
				    @foreach($news as $n)
						<tr>
							<td>{{ $n->title }}</td>
							<td>{{ $n->getHumanPublishedAt() }}</td>
							<td>
								@include('admin.news._actions', ["news" => $n])
							</td>
						</tr>
					@endforeach
				  </tbody>
				</table>

				<div class="centered">
					{!! $news->render() !!}
				</div>
			</div>
		</div>
		<!-- Panel end -->
	</div>
</div>

@stop
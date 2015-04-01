<a href="{{ route('admin.news.edit', $news->id) }}">
	<button type="button" class="btn btn-info btn-trans btn-xs btn-action " data-toggle="tooltip" data-placement="top" title="Edit News">
		<i class="fa fa-pencil-square-o"></i>
	</button>
</a>



{!! Form::model($news, ['data-remote' => true, 'data-callback' => 'removeTableRow', 'class' => 'remote-form', 'route' => ['admin.news.delete', $news->id]]) !!}
	<a href="#">
		<button type="button" class="btn btn-danger btn-trans btn-xs btn-action " data-toggle="tooltip" data-placement="top" title="Delete News" 
				onclick="customConfirm( this, 'Are you sure?', 'You will not be able to recover this news.', 'Deleted!', 'The news has been deleted.')" >
			<i class="fa fa-trash-o"></i>
		</button>
	</a>
{!! Form::close() !!}

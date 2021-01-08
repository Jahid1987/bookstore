@extends('admin.app')


@section('breadcrumb')
<li class="breadcrumb-item" aria-current="page">Categories</li>
<li class="breadcrumb-item active" aria-current="page">Trashed</li>
@endsection


@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
	<h2 class="h2">Trash Category List</h2>	
</div>
<div class="col-md-12">
	@if (session()->has('message'))
	<div class="alert alert-success">
		<ul>
			{{session('message')}}
		</ul>
	</div>
	@endif
</div>
<div class="table-responsive">
	<table class="table table-striped table-sm">
		<thead>
			<tr>
				<th>#</th>
				<th>Title</th>
				<th>Slug</th>
				<th>Description</th>
				<th>Deleted At</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			@if($trashes)
			@foreach($trashes as $trash)
			<tr>
				<td>{{$trash->id}}</td>
				<td>{{$trash->title}}</td>
				<td>{{$trash->slug}}</td>
				<td>{!! $trash->description !!}</td>
				<td>{{$trash->deleted_at}}</td>
				<td>
					<div class="btn-group" role="group" aria-label="Basic example">
						<a href="{{route('admin.category.restore', $trash->id)}}" role="button" type="button" class="btn btn-link">Restore</a>

						<form action="{{route('admin.category.forcedelete', $trash->id)}}" method="post">
							@csrf
							@method('GET')
							<button type="submit" class="btn btn-link">Delete</button>
						</form>

					</div>
				</td>
			</tr>
			@endforeach
			@else
			<tr>
				<td colspan="6">No trashegory is found!</td>
			</tr>
			@endif
		</tbody>
	</table>
</div>

<!-- Pagination -->
<div class="row">
	<div class="col-md-12">
		{{$trashes->links()}}
	</div>
</div>

@endsection
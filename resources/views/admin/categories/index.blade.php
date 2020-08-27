@extends('admin.app')
@section('breadcrumb')
    <li class="breadcrumb-item" aria-current="page">Categories</li>
    <li class="breadcrumb-item active" aria-current="page">Index</li>
@endsection
@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
	<h2 class="h2">Category List</h2>
	<div class="btn-toolbar mb-2 mb-md-0">
		<a href="{{route('admin.category.create')}}" class="btn btn-sm btn-outline-secondary">
			Add Category
		</a>
	</div>
	
</div>
<div class="table-responsive">
	<table class="table table-striped table-sm">
		<thead>
			<tr>
				<th>#</th>
				<th>Title</th>
				<th>Slug</th>
				<th>Description</th>
				<th>Childrens</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			@if($categories)
			@foreach($categories as $cat)
			<tr>
				<td>{{$cat->id}}</td>
				<td>{{$cat->title}}</td>
				<td>{{$cat->slug}}</td>
				<td>{!! $cat->description !!}</td>
				<td>
					@if($cat->childrens()->count() > 0)
					@foreach($cat->childrens as $child)
					{{$child->title}},
					@endforeach
					@else
					<strong>{{"Parent Category"}}</strong>
					@endif
				</td>
				<td>
					<a href="{{route('admin.category.edit', $cat->id)}}" class="btn btn-sm btn-info">Edit</a> |
					<a href="#" class="btn btn-sm btn-danger">Delete</a>
				</td>
			</tr>
			@endforeach
			@else
			<tr>
				<td colspan="6">No category is found!</td>
			</tr>
			@endif
		</tbody>
	</table>
</div>
@endsection
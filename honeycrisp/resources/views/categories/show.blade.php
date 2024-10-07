@extends ('layouts.app')

@section ('title', 'Category Details: ' . $category->name)

@section ('content')
<div class="container">
    <h1>Category Details: {{ $category->name }}</h1>
    <p>Category ID: {{ $category->id }}</p>
    <p>Category Description: {{ $category->description }}</p>
    <p>Facility: {{ $category->facility->name }}</p>
    <p>Created At: {{ $category->created_at }}</p>
    <p>Updated At: {{ $category->updated_at }}</p>

    <!-- Add more details as needed -->

    @can('update-category', $category)

    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-primary">Edit Category</a>
    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display: inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete Category</button>
    </form>

    @endcan
</div>

@endsection
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
</div>

@endsection
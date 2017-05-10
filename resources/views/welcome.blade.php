@extends('layouts/app')

@section('content')
    <component :is="currentView" source="/api/welcome"></component>
@endsection
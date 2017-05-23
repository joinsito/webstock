@extends('layouts/app')

@section('content')

<siteinfo siteid="{{$siteId}}" source="/api/siteinfo"></siteinfo>
@endsection
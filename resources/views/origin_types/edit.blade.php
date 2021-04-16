@extends('layouts.app_logged')
@section('content')
    <origin-type-edit :origin-type="{{ $origin_type }}" />
@endsection
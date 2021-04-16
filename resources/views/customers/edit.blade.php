@extends('layouts.app_logged')
@section('content')
    <customer-edit :customer="{{ $customer }}"/>
@endsection
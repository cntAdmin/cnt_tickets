@extends('layouts.app_logged')
@section('content')
    <tickets :statuses="{{ $ticket_statuses }}" />
@endsection
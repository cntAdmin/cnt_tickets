@extends('layouts.unlogged')
@section('content')
    <ticket-view
        :ticket="{{ $ticket }}"
        :permissions="{{ auth()->user() ? auth()->user()->getPermissionsViaRoles() : 'null' }}"
        :user-role="{{ auth()->user() ? auth()->user()->roles[0]->id : 'null' }}"
        type="annonymous"
    />
@endsection
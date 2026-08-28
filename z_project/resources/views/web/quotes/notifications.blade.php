@extends('layouts.admin')

@section('content')
    <h1>Admin Notifications</h1>

    <ul>
        @foreach ($notifications as $notification)
            <li>
                New quote request ID: {{ $notification->quote_id }}
                <form action="{{ route('admin.mark_notification_as_read', $notification->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit">Mark as Read</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection

@extends('layouts.app')

@section('title', 'Access Requests')

@section('content')
    <div class="container">
        <div class="panel">
            <div class="panel-header">
                <i class="fas fa-unlock-alt color-accent header-icon"></i> Access Requests
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($requests as $request)
                    <tr>
                        <td>{{ $request->name }}</td>
                        <td>{{ $request->email }}</td>
                        <td class="text-right">
                            <form action="{{ route('request-access.review', ['access_request' => $request]) }}" method="post">
                                {{ csrf_field() }}
                                <button type="submit" class="btn bg-green" name="approve">Approve</button>
                                <button type="submit" class="btn bg-red" name="deny">Deny</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr class="empty text-center">
                        <td colspan="100"><i>No access requests found</i></td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('styles')
<style>
    .admin-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .admin-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-color);
    }

    .card {
        background-color: var(--secondary-bg);
        border: 1px solid var(--border-color);
        border-radius: 0.5rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
    }

    .card-header {
        background-color: rgba(0, 0, 0, 0.2);
        border-bottom: 1px solid var(--border-color);
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-color);
    }

    .card-body {
        color: var(--text-color);
        padding: 1.5rem;
    }

    .table {
        color: var(--text-color);
        margin-bottom: 1.5rem;
    }

    .table th, 
    .table td,
    .table thead th {
        color: var(--text-color) !important;
        padding: 0.75rem;
        border-bottom: 1px solid var(--border-color);
    }

    .table-striped > tbody > tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.1);
    }

    .list-group-item {
        background-color: var(--secondary-bg);
        border: 1px solid var(--border-color);
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        padding: 1rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-outline-cyan {
        border-color: var(--primary-color);
        color: var(--primary-color);
        border-radius: 8px;
        font-weight: 500;
        padding: 8px 16px;
        transition: all 0.3s ease;
    }

    .btn-outline-cyan:hover {
        background-color: var(--primary-color);
        color: #000;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 200, 227, 0.3);
    }
</style>
@endsection

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <h1 class="admin-title">Admin Dashboard</h1>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Blogs</h5>
                    <p class="card-text display-4">{{ $stats['total_blogs'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Comments</h5>
                    <p class="card-text display-4">{{ $stats['total_comments'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text display-4">{{ $stats['total_users'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">User Management</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['users'] as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->isAdmin())
                                            <span class="badge bg-primary">Admin</span>
                                        @elseif($user->isBanned())
                                            <span class="badge bg-danger">Banned</span>
                                        @else
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$user->isAdmin())
                                            @if($user->isBanned())
                                                <form action="{{ route('admin.users.unban', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="bi bi-check-circle"></i> Unban
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.users.ban', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning">
                                                        <i class="bi bi-x-circle"></i> Ban
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $stats['users']->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
@extends('layouts.admin')

@section('title', 'Activities Management')

@section('page-title', 'Activities Management')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Activities List</h5>
        <a href="{{ route('activities.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i> Add New Activity
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Participants</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($activity->image)
                                <img src="{{ asset('storage/'.$activity->image) }}" width="50" height="50" class="rounded" style="object-fit: cover;">
                            @else
                                <img src="{{ asset('images/default-activity.jpg') }}" width="50" height="50" class="rounded">
                            @endif
                        </td>
                        <td>
                            <span class="d-inline-block text-truncate" style="max-width: 150px;">
                                {{ $activity->title }}
                            </span>
                        </td>
                        <td>
                            @if($activity->type == 'join')
                                <span class="badge bg-info">Join</span>
                            @elseif($activity->type == 'donate')
                                <span class="badge bg-success">Donate</span>
                            @else
                                <span class="badge bg-primary">Both</span>
                            @endif
                        </td>
                        <td>
                            <span class="d-inline-block text-truncate" style="max-width: 100px;">
                                {{ $activity->location }}
                            </span>
                        </td>
                        <td>{{ $activity->date->format('Y-m-d') }}</td>
                        <td>
                            @if($activity->status == 'upcoming')
                                <span class="badge bg-warning">Upcoming</span>
                            @elseif($activity->status == 'done')
                                <span class="badge bg-success">Completed</span>
                            @else
                                <span class="badge bg-danger">Cancelled</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary">
                                {{ $activity->participants->count() }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('activities.show', $activity) }}" class="btn btn-sm btn-info text-white">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('activities.edit', $activity) }}" class="btn btn-sm btn-warning text-white">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteActivityModal{{ $activity->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            
                            <!-- Delete Activity Modal -->
                            <div class="modal fade" id="deleteActivityModal{{ $activity->id }}" tabindex="-1" aria-labelledby="deleteActivityModalLabel{{ $activity->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteActivityModalLabel{{ $activity->id }}">Confirm Deletion</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete the activity "{{ $activity->title }}"?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('activities.destroy', $activity) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No activities found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-center">
            {{ $activities->links() }}
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('title', 'Activity Details')

@section('page-title', 'Activity Details')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Activity Information</h5>
        <div>
            <a href="{{ route('activities.edit', $activity) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <a href="{{ route('activities.index') }}" class="btn btn-secondary ms-2">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 25%">Title</th>
                        <td>{{ $activity->title }}</td>
                    </tr>
                    <tr>
                        <th>Category</th>
                        <td>{{ $activity->category->name }}</td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td>
                            @if($activity->type == 'join')
                                <span class="badge bg-info">Join</span>
                            @elseif($activity->type == 'donate')
                                <span class="badge bg-success">Donate</span>
                            @else
                                <span class="badge bg-primary">Join & Donate</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($activity->status == 'upcoming')
                                <span class="badge bg-warning">Upcoming</span>
                            @elseif($activity->status == 'done')
                                <span class="badge bg-success">Completed</span>
                            @else
                                <span class="badge bg-danger">Cancelled</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Location</th>
                        <td>{{ $activity->location }}</td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>{{ $activity->date->format('F j, Y, g:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Organizer</th>
                        <td>{{ $activity->creator->full_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Current Participants</th>
                        <td>{{ $activity->participants->count() }}</td>
                    </tr>
                    <tr>
                        <th>Donation Goal</th>
                        <td>{{ $activity->donation_goal ? '$'.number_format($activity->donation_goal, 2) : 'Not set' }}</td>
                    </tr>
                    <tr>
                        <th>Total Donations</th>
                        <td>${{ number_format($activity->total_donations, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Progress</th>
                        <td>
                            @if($activity->donation_goal)
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $activity->donation_percentage ?? 0 }}%;" aria-valuenow="{{ $activity->donation_percentage ?? 0 }}" aria-valuemin="0" aria-valuemax="100">{{ $activity->donation_percentage ?? 0 }}%</div>
                            </div>
                            @else
                            <span class="text-muted">No donation goal set</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $activity->description }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Activity Image</div>
                    <div class="card-body text-center">
                        @if($activity->image)
                            <img src="{{ asset('storage/'.$activity->image) }}" class="img-fluid rounded">
                        @else
                            <img src="{{ asset('images/default-activity.jpg') }}" class="img-fluid rounded">
                        @endif
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Participants ({{ $activity->participants->count() }})</span>
                        <a href="#" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($activity->participants->take(5) as $participant)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $participant->full_name }}
                                <span class="badge bg-secondary">{{ $participant->pivot->joined_at->format('M j, Y') }}</span>
                            </li>
                            @empty
                            <li class="list-group-item text-center">No participants yet</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                
                <!-- Delete Activity Button -->
                <div class="mt-4">
                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteActivityModal">
                        <i class="fas fa-trash me-1"></i> Delete Activity
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Activity Modal -->
<div class="modal fade" id="deleteActivityModal" tabindex="-1" aria-labelledby="deleteActivityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteActivityModalLabel">Confirm Deletion</h5>
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
@endsection
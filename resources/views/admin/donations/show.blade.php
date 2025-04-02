<!-- resources/views/admin/donations/show.blade.php -->
@extends('layouts.admin')

@section('title', 'Donation Details')

@section('page-title', 'Donation Details')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Donation Information</h5>
        <div>
            <a href="{{ route('donations.edit', $donation) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <a href="{{ route('donations.index') }}" class="btn btn-secondary ms-2">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 25%">Donation ID</th>
                        <td>{{ $donation->id }}</td>
                    </tr>
                    <tr>
                        <th>Donor</th>
                        <td>
                            @if($donation->user)
                                <a href="{{ route('users.show', $donation->user) }}">
                                    {{ $donation->user->full_name }}
                                </a>
                            @else
                                Unknown
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Activity</th>
                        <td>
                            @if($donation->activity)
                                <a href="{{ route('activities.show', $donation->activity) }}">
                                    {{ $donation->activity->title }}
                                </a>
                            @else
                                Unknown
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Amount</th>
                        <td>{{ number_format($donation->amount, 2) }} SR</td>
                    </tr>
                    <tr>
                        <th>Payment Method</th>
                        <td>
                            @if($donation->payment_method == 'credit_card')
                                <span class="badge bg-info">Credit Card</span>
                            @elseif($donation->payment_method == 'bank_transfer')
                                <span class="badge bg-primary">Bank Transfer</span>
                            @elseif($donation->payment_method == 'cash')
                                <span class="badge bg-secondary">Cash</span>
                            @else
                                <span class="badge bg-dark">{{ $donation->payment_method }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Transaction ID</th>
                        <td>{{ $donation->transaction_id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($donation->status == 'completed')
                                <span class="badge bg-success">Completed</span>
                            @elseif($donation->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @else
                                <span class="badge bg-danger">Failed</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Donation Date</th>
                        <td>{{ $donation->donated_at ? $donation->donated_at->format('F j, Y g:i A') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $donation->created_at->format('F j, Y g:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $donation->updated_at->format('F j, Y g:i A') }}</td>
                    </tr>
                    @if($donation->notes)
                    <tr>
                        <th>Notes</th>
                        <td>{{ $donation->notes }}</td>
                    </tr>
                    @endif
                </table>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Activity Information</div>
                    <div class="card-body">
                        @if($donation->activity)
                            <h6>{{ $donation->activity->title }}</h6>
                            <div class="mb-3">
                                @if($donation->activity->image)
                                    <img src="{{ asset('storage/'.$donation->activity->image) }}" class="img-fluid rounded mb-2">
                                @endif
                            </div>
                            <p><strong>Category:</strong> {{ $donation->activity->category->name ?? 'N/A' }}</p>
                            <p><strong>Status:</strong> 
                                @if($donation->activity->status == 'upcoming')
                                    <span class="badge bg-warning">Upcoming</span>
                                @elseif($donation->activity->status == 'done')
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </p>
                            <p><strong>Date:</strong> {{ $donation->activity->date->format('F j, Y') }}</p>
                            <p><strong>Donation Goal:</strong> {{ $donation->activity->donation_goal ? number_format($donation->activity->donation_goal, 2).' SR' : 'Not set' }}</p>
                            
                            @if($donation->activity->donation_goal)
                            <div class="progress mb-2">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $donation->activity->donation_percentage ?? 0 }}%;" aria-valuenow="{{ $donation->activity->donation_percentage ?? 0 }}" aria-valuemin="0" aria-valuemax="100">{{ $donation->activity->donation_percentage ?? 0 }}%</div>
                            </div>
                            <p class="text-center"><small>{{ number_format($donation->activity->total_donations, 2) }} of {{ number_format($donation->activity->donation_goal, 2) }} SR</small></p>
                            @endif
                            
                            <div class="d-grid">
                                <a href="{{ route('activities.show', $donation->activity) }}" class="btn btn-primary">
                                    <i class="fas fa-eye me-1"></i> View Activity
                                </a>
                            </div>
                        @else
                            <div class="alert alert-warning">Activity information not available</div>
                        @endif
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header">Actions</div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('donations.edit', $donation) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i> Edit Donation
                            </a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteDonationModal">
                                <i class="fas fa-trash me-1"></i> Delete Donation
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteDonationModal" tabindex="-1" aria-labelledby="deleteDonationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteDonationModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this donation of {{ number_format($donation->amount, 2) }} SR?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('donations.destroy', $donation) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
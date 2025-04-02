<!-- resources/views/admin/donations/index.blade.php -->
@extends('layouts.admin')

@section('title',' Donation Management')

@section('page-title',' Donation Management')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card bg-primary text-white shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Total Donations</h5>
                        <h1 class="display-5 mb-0">{{ number_format($totalDonations, 2) }} riyal</h1>
                    </div>
                    <div>
                        <i class="fas fa-hand-holding-usd fa-4x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Donation List</h5>
        <a href="{{ route('donations.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i> Add a new donation
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>donor</th>
                        <th>The amount</th>
                        <th>Payment method</th>
                        <th>Transaction number</th>
                        <th>the condition</th>
                        <th>the date</th>
                        <th>procedures</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donations as $donation)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $donation->user->name ?? 'unknown' }}</td>
                        <td>{{ number_format($donation->amount, 2) }} riyal</td>
                        <td>{{ $donation->payment_method }}</td>
                        <td>{{ $donation->transaction_id ?? '-' }}</td>
                        <td>
                            @if($donation->status == 'completed')
                            <span class="badge bg-success">complete</span>
                            @elseif($donation->status == 'pending')
                            <span class="badge bg-warning">Under Processing</span>
                            @else
                            <span class="badge bg-danger">to fail</span>
                            @endif
                        </td>
                        <td>{{ $donation->created_at->format('Y-m-d') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('donations.show', $donation) }}" class="btn btn-sm btn-info text-white">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('donations.edit', $donation) }}" class="btn btn-sm btn-warning text-white">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteDonationModal{{ $donation->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            
                            <!-- Modal حذف التبرع -->
                            <div class="modal fade" id="deleteDonationModal{{ $donation->id }}" tabindex="-1" aria-labelledby="deleteDonationModalLabel{{ $donation->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteDonationModalLabel{{ $donation->id }}">تأكيد الحذف</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                        Are you sure you want to delete the donation amount?{{ number_format($donation->amount, 2) }} Riyal?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">cancellation</button>
                                            <form action="{{ route('donations.destroy', $donation) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Confirm deletion</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No donations</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-center">
            {{ $donations->links() }}
        </div>
    </div>
</div>
@endsection
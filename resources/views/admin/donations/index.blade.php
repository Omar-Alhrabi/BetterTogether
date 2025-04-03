@extends('layouts.admin')

@section('title', 'Donations Dashboard')

@section('page-title', 'Donations Dashboard')

@section('styles')
<style>
    .stats-card {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.15);
    }
    .stats-icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }
    .stats-value {
        font-size: 2rem;
        font-weight: 700;
    }
    .donation-row {
        transition: all 0.2s ease;
    }
    .donation-row:hover {
        background-color: rgba(0,0,0,0.03);
        transform: scale(1.01);
    }
    .donation-amount {
        font-weight: 700;
        color: #28a745;
    }
    .card-header-tabs .nav-link {
        border-radius: 0;
        padding: 0.75rem 1.25rem;
        font-weight: 500;
    }
    .card-header-tabs .nav-link.active {
        border-bottom: 3px solid #007bff;
        background-color: transparent;
        color: #007bff;
    }
</style>
@endsection

@section('content')
<!-- Donation Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="stats-card card border-left-primary h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Donations</div>
                        <div class="stats-value text-gray-800">{{ number_format($totalDonations, 2) }} SR</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-hand-holding-usd stats-icon text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="stats-card card border-left-warning h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Total Donors</div>
                        <div class="stats-value text-gray-800">{{ $totalDonors }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users stats-icon text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="stats-card card border-left-info h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Average Donation</div>
                        <div class="stats-value text-gray-800">{{ number_format($avgDonation, 2) }} SR</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-coins stats-icon text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Donations List -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">All Donations</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-borderless table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Donor</th>
                        <th>Activity</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Date</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donations as $donation)
                    <tr class="donation-row">
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-2 bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%;">
                                    <i class="fas fa-user text-secondary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $donation->user->name ?? 'Unknown' }}</h6>
                                    <small class="text-muted">ID: #{{ $donation->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($donation->activity)
                                <span class="badge bg-light text-dark">{{ $donation->activity->title }}</span>
                            @else
                                <span class="badge bg-secondary">No activity</span>
                            @endif
                        </td>
                        <td class="donation-amount">{{ number_format($donation->amount, 2) }} SR</td>
                        <td>
                            @if($donation->payment_method == 'credit_card')
                                <i class="far fa-credit-card me-1"></i> Credit Card
                            @elseif($donation->payment_method == 'bank_transfer')
                                <i class="fas fa-university me-1"></i> Bank Transfer
                            @elseif($donation->payment_method == 'cash')
                                <i class="fas fa-money-bill-wave me-1"></i> Cash
                            @else
                                <i class="fas fa-money-bill-alt me-1"></i> {{ $donation->payment_method ?? 'Other' }}
                            @endif
                        </td>
                        <td>
                            <div>{{ $donation->created_at->format('M d, Y') }}</div>
                            <small class="text-muted">{{ $donation->created_at->format('h:i A') }}</small>
                        </td>
                        <td>
                            <a href="{{ route('donations.show', $donation) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <i class="fas fa-donate fa-3x text-muted mb-3"></i>
                                <h5>No donations found</h5>
                                <p class="text-muted">There are no donations in the system yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <small class="text-muted">Showing {{ $donations->count() }} of {{ $donations->total() }} donations</small>
            </div>
            <div class="pagination-container">
                {{ $donations->links() }}
            </div>
        </div>
    </div>
</div>

@if(Route::has('donations.statistics'))
<!-- Donation Statistics Link -->
<div class="text-center mb-4">
    <a href="{{ route('donations.statistics') }}" class="btn btn-primary">
        <i class="fas fa-chart-bar me-2"></i> View Detailed Donation Statistics
    </a>
</div>
@endif
@endsection
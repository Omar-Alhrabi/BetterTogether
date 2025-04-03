@extends('layouts.admin')

@section('title', 'Donation Statistics')

@section('page-title', 'Donation Statistics')

@section('styles')
<style>
    .stats-card {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 20px;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.15);
    }
    .stats-icon {
        font-size: 3rem;
        opacity: 0.8;
    }
    .stats-value {
        font-size: 2.5rem;
        font-weight: 700;
    }
    .chart-container {
        position: relative;
        height: 400px;
        margin-bottom: 30px;
    }
    .activity-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .activity-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .progress {
        height: 10px;
        border-radius: 5px;
    }
    .back-button {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
        border-radius: 50px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .back-button:hover {
        transform: translateX(-5px);
    }
</style>
@endsection

@section('content')
<!-- Back button -->
<div class="mb-4">
    <a href="{{ route('donations.index') }}" class="btn btn-outline-secondary back-button">
        <i class="fas fa-arrow-left me-2"></i> Back to Donations
    </a>
</div>

<!-- Main Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-4 col-md-6">
        <div class="stats-card card bg-primary text-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2">Total Donations</h6>
                        <div class="stats-value">{{ number_format($totalDonations, 2) }} SR</div>
                    </div>
                    <div>
                        <i class="fas fa-hand-holding-usd stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6">
        <div class="stats-card card bg-success text-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2">Total Activities</h6>
                        <div class="stats-value">{{ $donationsByActivity->count() }}</div>
                    </div>
                    <div>
                        <i class="fas fa-calendar-alt stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6">
        <div class="stats-card card bg-info text-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2">Avg. Activity Amount</h6>
                        <div class="stats-value">
                            @if($donationsByActivity->count() > 0)
                                {{ number_format($totalDonations / $donationsByActivity->count(), 2) }} SR
                            @else
                                0.00 SR
                            @endif
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-chart-line stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Donations by Activity -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i> Donations by Activity</h5>
    </div>
    <div class="card-body">
        @if($donationsByActivity->count() > 0)
            @foreach($donationsByActivity as $activity)
            <div class="activity-card card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="mb-1">{{ $activity->title }}</h5>
                            <div class="text-muted">
                                <span class="me-3"><i class="fas fa-users me-1"></i> {{ $activity->donations_count }} Donors</span>
                                @if(isset($activity->date) && ($activity->date instanceof \DateTime || is_string($activity->date)))
                                    <span><i class="far fa-calendar-alt me-1"></i> 
                                    @if($activity->date instanceof \DateTime)
                                        {{ $activity->date->format('M d, Y') }}
                                    @else
                                        {{ date('M d, Y', strtotime($activity->date)) }}
                                    @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <h4 class="text-success mb-0">{{ number_format($activity->total_donations ?? 0, 2) }} SR</h4>
                        </div>
                    </div>
                    
                    @if(isset($activity->donation_goal) && $activity->donation_goal > 0)
                    <div>
                        <div class="d-flex justify-content-between text-muted mb-1">
                            <small>Progress toward goal</small>
                            <small>{{ number_format(($activity->total_donations / $activity->donation_goal) * 100, 1) }}%</small>
                        </div>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-success" role="progressbar" 
                                style="width: {{ min(($activity->total_donations / $activity->donation_goal) * 100, 100) }}%;" 
                                aria-valuenow="{{ min(($activity->total_donations / $activity->donation_goal) * 100, 100) }}" 
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="text-center text-muted">
                            <small>{{ number_format($activity->total_donations, 2) }} of {{ number_format($activity->donation_goal, 2) }} SR</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        @else
            <div class="text-center py-5">
                <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                <h5>No donation data available</h5>
                <p class="text-muted">There are no activities with donations yet.</p>
            </div>
        @endif
    </div>
</div>

@endsection
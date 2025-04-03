@extends('layouts.admin')

@section('title', 'Donation Details')

@section('page-title', 'Donation Details')

@section('styles')
<style>
    /* Styles remain the same */
    .donation-card {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s ease;
    }
    
    .donation-header {
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        color: white;
        padding: 25px;
        position: relative;
    }
    
    .donation-header .background-icon {
        position: absolute;
        right: 25px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 5rem;
        opacity: 0.2;
    }
    
    .donation-amount {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 10px;
    }
    
    .donation-meta {
        color: rgba(255, 255, 255, 0.8);
    }
    
    .info-section {
        padding: 30px;
    }
    
    .info-section h5 {
        border-bottom: 2px solid #f5f5f5;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    
    .info-item {
        padding: 12px 0;
        border-bottom: 1px solid #f5f5f5;
        display: flex;
        align-items: center;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        width: 35%;
        color: #6c757d;
    }
    
    .avatar-container {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
    }
    
    .avatar-icon {
        font-size: 2rem;
        color: #6c757d;
    }
    
    .activity-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
        height: 100%;
    }
    
    .activity-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    
    .activity-card .card-img-top {
        height: 180px;
        object-fit: cover;
    }
    
    .related-donation-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 8px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        cursor: pointer;
        margin-bottom: 15px;
    }
    
    .related-donation-card:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 5px;
        height: calc(100% - 10px);
        width: 2px;
        background-color: #dee2e6;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 25px;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 0;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background-color: #fff;
        border: 2px solid #007bff;
        z-index: 1;
    }
    
    .timeline-content {
        padding-left: 10px;
    }
    
    .timeline-date {
        color: #6c757d;
        font-size: 0.85rem;
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
<!-- Back to list button -->
<div class="mb-4">
    <a href="{{ route('donations.index') }}" class="btn btn-outline-secondary back-button">
        <i class="fas fa-arrow-left me-2"></i> Back to Donations
    </a>
</div>

<div class="row">
    <!-- Main Donation Information -->
    <div class="col-lg-8 mb-4">
        <div class="donation-card">
            <div class="donation-header">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="badge bg-light text-dark mb-2">Donation #{{ $donation->id }}</span>
                        <h3 class="donation-amount">{{ number_format($donation->amount, 2) }} SR</h3>
                        <div class="donation-meta d-flex align-items-center">
                            <span><i class="far fa-calendar-alt me-1"></i> 
                            @if($donation->donated_at instanceof \DateTime) 
                                {{ $donation->donated_at->format('F j, Y') }}
                            @elseif(is_string($donation->donated_at))
                                {{ date('F j, Y', strtotime($donation->donated_at)) }}
                            @else
                                {{ $donation->created_at->format('F j, Y') }}
                            @endif
                            </span>
                        </div>
                    </div>
                </div>
                <i class="fas fa-hand-holding-usd background-icon"></i>
            </div>
            
            <div class="info-section">
                <h5><i class="fas fa-info-circle me-2"></i> Donation Information</h5>
                
                <div class="info-item">
                    <span class="info-label">Donor</span>
                    <div class="d-flex align-items-center">
                        <div class="avatar-container">
                            <i class="fas fa-user avatar-icon"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">{{ $donation->user->full_name ?? 'Unknown' }}</h6>
                            @if($donation->user)
                                <small class="text-muted">{{ $donation->user->email ?? 'No email available' }}</small>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Payment Method</span>
                    <div>
                        @if($donation->payment_method == 'credit_card')
                            <div class="d-flex align-items-center">
                                <span class="badge bg-info me-2 p-2"><i class="far fa-credit-card"></i></span>
                                <span>Credit Card</span>
                            </div>
                        @elseif($donation->payment_method == 'bank_transfer')
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary me-2 p-2"><i class="fas fa-university"></i></span>
                                <span>Bank Transfer</span>
                            </div>
                        @elseif($donation->payment_method == 'cash')
                            <div class="d-flex align-items-center">
                                <span class="badge bg-secondary me-2 p-2"><i class="fas fa-money-bill-wave"></i></span>
                                <span>Cash</span>
                            </div>
                        @else
                            <div class="d-flex align-items-center">
                                <span class="badge bg-dark me-2 p-2"><i class="fas fa-money-bill-alt"></i></span>
                                <span>{{ $donation->payment_method ?? 'Not specified' }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Transaction ID</span>
                    <span>{{ $donation->transaction_id ?? 'N/A' }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Donation Date</span>
                    <span>
                    @if($donation->donated_at instanceof \DateTime) 
                        {{ $donation->donated_at->format('F j, Y g:i A') }}
                    @elseif(is_string($donation->donated_at) && !empty($donation->donated_at))
                        {{ date('F j, Y g:i A', strtotime($donation->donated_at)) }}
                    @else
                        N/A
                    @endif
                    </span>
                </div>
                
                @if($donation->notes)
                <div class="info-item">
                    <span class="info-label">Notes</span>
                    <div class="bg-light p-3 rounded">
                        {{ $donation->notes }}
                    </div>
                </div>
                @endif
            </div>
            
            <div class="info-section pt-0">
                <h5><i class="fas fa-history me-2"></i> Timeline</h5>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Donation Recorded</h6>
                            <p class="timeline-date">
                                @if($donation->created_at instanceof \DateTime)
                                    {{ $donation->created_at->format('F j, Y g:i A') }}
                                @else
                                    {{ date('F j, Y g:i A', strtotime($donation->created_at)) }}
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    @if($donation->donated_at && 
                       ((is_object($donation->donated_at) && $donation->donated_at->format('Y-m-d H:i:s') != $donation->created_at->format('Y-m-d H:i:s')) ||
                        (is_string($donation->donated_at) && date('Y-m-d H:i:s', strtotime($donation->donated_at)) != $donation->created_at->format('Y-m-d H:i:s'))))
                    <div class="timeline-item">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Donation Date</h6>
                            <p class="timeline-date">
                                @if($donation->donated_at instanceof \DateTime)
                                    {{ $donation->donated_at->format('F j, Y g:i A') }}
                                @elseif(is_string($donation->donated_at))
                                    {{ date('F j, Y g:i A', strtotime($donation->donated_at)) }}
                                @endif
                            </p>
                        </div>
                    </div>
                    @endif
                    
                    @if(is_object($donation->updated_at) && $donation->updated_at->format('Y-m-d H:i:s') != $donation->created_at->format('Y-m-d H:i:s'))
                    <div class="timeline-item">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Last Updated</h6>
                            <p class="timeline-date">{{ $donation->updated_at->format('F j, Y g:i A') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sidebar Information -->
    <div class="col-lg-4">
        <!-- Activity Information -->
        @if($donation->activity)
        <div class="activity-card card mb-4">
            <div class="position-relative">
                @if($donation->activity->image)
                    <img src="{{ asset('storage/'.$donation->activity->image) }}" class="card-img-top" alt="{{ $donation->activity->title }}">
                @else
                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 180px; width: 100%;">
                        <i class="fas fa-calendar-alt fa-3x text-muted"></i>
                    </div>
                @endif
                @if(isset($donation->activity->status))
                <div class="position-absolute top-0 end-0 p-2">
                    @if($donation->activity->status == 'upcoming')
                        <span class="badge bg-warning">Upcoming</span>
                    @elseif($donation->activity->status == 'completed' || $donation->activity->status == 'done')
                        <span class="badge bg-success">Completed</span>
                    @else
                        <span class="badge bg-danger">Cancelled</span>
                    @endif
                </div>
                @endif
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $donation->activity->title }}</h5>
                <p class="card-text">{{ Str::limit($donation->activity->description ?? 'No description available', 100) }}</p>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between text-muted mb-1">
                        <small>Donation Progress</small>
                        @if(isset($donation->activity->donation_goal) && $donation->activity->donation_goal)
                            <small>{{ number_format($donation->activity->total_donations ?? 0, 2) }} / {{ number_format($donation->activity->donation_goal, 2) }} SR</small>
                        @endif
                    </div>
                    
                    @if(isset($donation->activity->donation_goal) && $donation->activity->donation_goal)
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ is_numeric($donation->activity->donation_percentage ?? 0) ? $donation->activity->donation_percentage : 0 }}%;" aria-valuenow="{{ is_numeric($donation->activity->donation_percentage ?? 0) ? $donation->activity->donation_percentage : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    @else
                        <div class="alert alert-info py-1 px-2 mb-0">
                            <small>No donation goal set</small>
                        </div>
                    @endif
                </div>
                
                <div class="d-flex flex-wrap">
                    <div class="me-3 mb-2">
                        <small class="text-muted d-block">Date</small>
                        <i class="far fa-calendar-alt me-1"></i> 
                        @if(isset($donation->activity->date) && $donation->activity->date instanceof \DateTime)
                            {{ $donation->activity->date->format('M d, Y') }}
                        @elseif(isset($donation->activity->date) && is_string($donation->activity->date))
                            {{ date('M d, Y', strtotime($donation->activity->date)) }}
                        @else
                            Not set
                        @endif
                    </div>
                    
                    @if(isset($donation->activity->location) && $donation->activity->location)
                    <div class="mb-2">
                        <small class="text-muted d-block">Location</small>
                        <i class="fas fa-map-marker-alt me-1"></i> {{ $donation->activity->location }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @else
        <div class="card mb-4">
            <div class="card-body text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h5>No Activity Information</h5>
                <p class="text-muted">This donation is not associated with any activity or the activity has been deleted.</p>
            </div>
        </div>
        @endif
        
        <!-- Related Donations -->
        @if($relatedDonations && $relatedDonations->count() > 0)
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-link me-2"></i> Related Donations</h5>
            </div>
            <div class="card-body">
                @foreach($relatedDonations as $relatedDonation)
                <a href="{{ route('donations.show', $relatedDonation) }}" class="text-decoration-none">
                    <div class="related-donation-card p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ number_format($relatedDonation->amount, 2) }} SR</h6>
                                <small class="text-muted">
                                    @if($relatedDonation->user_id == $donation->user_id)
                                        <i class="fas fa-user me-1"></i> Same donor
                                    @else
                                        <i class="fas fa-calendar-alt me-1"></i> Same activity
                                    @endif
                                </small>
                            </div>
                            <div>
                                <span class="badge bg-primary"><i class="fas fa-eye"></i> View</span>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
<style>
    .qr-container {
        width: 100%;
        height: 100%;
        margin: 0 auto;
        padding: 20px;
    }

    .qr-grid {
        display: grid;
        gap: 20px;
        background-color: #ffffff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    .dark .qr-grid {
        background-color: #18181B;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    }

    .qr-column {
        padding: 24px;
    }

    .qr-code-section {
        text-align: center;
    }

    .qr-title {
        font-size: 24px;
        font-weight: bold;
        color: #18181B;
        margin-bottom: 16px;
    }

    .dark .qr-title {
        color: #f9fafb;
    }

    .qr-code-container {
        display: inline-block;
        width: 100%;
        height: 100%;
        background: #FFFFFFFF;
        padding: 2px;
    }

    .qr-code-container svg {
        width: 100%;
        height: 100%;
    }

    .info-section h3 {
        font-size: 18px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 12px;
    }

    .dark .info-section h3 {
        color: #d1d5db;
    }

    .info-item {
        margin-bottom: 8px;
        font-size: 14px;
        color: #111827;
    }

    .dark .info-item {
        color: #e5e7eb;
    }

    .no-data {
        color: #6b7280;
        text-align: center;
        font-style: italic;
    }

    .dark .no-data {
        color: #9ca3af;
    }

    /* Responsive Grid */
    @media (min-width: 1024px) {
        .qr-grid {
            grid-template-columns: 1fr 1fr 1fr;
        }
    }

    @media (min-width: 768px) and (max-width: 1023px) {
        .qr-grid {
            grid-template-columns: 1fr 1fr;
        }

        .qr-code-section {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 767px) {
        .qr-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="qr-container">
    @if (auth()->check() && auth()->user()->qr_code)
        @php
            $activeSubscription = auth()->user()->subscriptions()->where('status', 'active')->first();
        @endphp
        <div class="qr-grid">
            <div class="qr-column qr-code-section">
                <div class="qr-code-container">
                    @if ($activeSubscription)
                        @php
                            $secret = env('QR_SECRET', 'gms_secret_key_2024');

                            $data = [
                                'qr_code' => auth()->user()->qr_code,
                                'timestamp' => time(),
                            ];

                            $json = json_encode($data);
                            $signature = hash('sha256', $json . $secret);

                            $payload = base64_encode(
                                json_encode([
                                    'data' => $json,
                                    'signature' => $signature,
                                ]),
                            );
                        @endphp

                        {!! QrCode::size(250)->generate($payload) !!}
                    @else
                        {!! QrCode::size(250)->generate('This member has no active subscription') !!}
                    @endif
                </div>
            </div>

            <div class="qr-column">
                <h3 class="qr-title">User Information</h3>
                <div>
                    <p class="info-item"><strong>Name:</strong> {{ auth()->user()->name }}</p>
                    <p class="info-item"><strong>Email:</strong> {{ auth()->user()->email }}</p>
                    <p class="info-item"><strong>Role:</strong> {{ ucfirst(auth()->user()->role) }}</p>
                    <p class="info-item"><strong>Status:</strong> {{ ucfirst(auth()->user()->status) }}</p>
                </div>
            </div>

            <div class="qr-column">
                <h3 class="qr-title">Active Subscription</h3>
                @if ($activeSubscription)
                    <div>
                        <p class="info-item"><strong>Fitness Offer:</strong>
                            {{ $activeSubscription->fitnessOffer->name ?? 'N/A' }}</p>
                        <p class="info-item"><strong>Coach:</strong> {{ $activeSubscription->coach->name ?? 'N/A' }}</p>
                        <p class="info-item"><strong>Registered Date:</strong>
                            {{ $activeSubscription->start_date->format('M d, Y') }}</p>
                        <p class="info-item"><strong>Expire On:</strong>
                            {{ $activeSubscription->end_date->format('M d, Y') }}</p>
                    </div>
                @else
                    <div>
                        <p class="info-item"><strong>Fitness Offer:</strong>
                            {{ $activeSubscription->fitnessOffer->name ?? 'N/A' }}</p>
                        <p class="info-item"><strong>Coach:</strong> {{ $activeSubscription->coach->name ?? 'N/A' }}</p>
                        <p class="info-item"><strong>Registered Date:</strong>
                            {{ 'N/A' }}</p>
                        <p class="info-item"><strong>Expire On:</strong>
                            {{ 'N/A' }}</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="qr-grid">
            <div class="qr-column">
                <p class="no-data">No QR code available</p>
            </div>
        </div>
    @endif
</div>

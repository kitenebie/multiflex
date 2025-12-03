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
                <br>
                <br>
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

            <div class="qr-column">
                @foreach ($fitnessOffers as $index => $offer)
                    @if (auth()->check() && auth()->user()->role == 'member' && auth()->user()->status == 'active')
                        @if (auth()->user()->subscriptions()->where('end_date', '>', now())->exists())
                            <div
                                class="pricing-card {{ $index === 0 ? 'first' : '' }} {{ $index === count($fitnessOffers) - 1 ? 'last' : '' }}">
                                <h3>{{ $offer->name }}</h3>
                                <div class="price">
                                    <span class="amount">₱{{ $offer->price }}</span>
                                    <span class="duration">/{{ $offer->duration_days }} days</span>
                                </div>
                                <p class="summary">
                                    {{ $offer->description[0]['fitness_offered'] ?? 'A great plan for your fitness journey.' }}
                                </p>
                                <ul>
                                    @forelse ($offer->description[0]['includes'] as $include)
                                        <li>
                                            <svg viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" />
                                            </svg>
                                            {{ $include['sub_fitness_offered'] }}
                                        </li>
                                    @empty
                                    @endforelse
                                </ul>
                            </div>
                        @endif
                    @endif
                @endforeach
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

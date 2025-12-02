<div class="pricing-section">
  <div class="pricing-bg" aria-hidden="true">
    <div class="pricing-bg-inner"></div>
  </div>

  <div class="pricing-title">
    <h2>Pricing</h2>
    <p>Choose the right plan for you</p>
  </div>

  <p class="pricing-subtitle">
    Choose an affordable fitness plan packed with top-tier training programs, expert coaching, and facilities to help you hit your health goals.
  </p>

  <div class="pricing-cards" style="--cards-count: {{ count($fitnessOffers) }}">
    @foreach($fitnessOffers as $index => $offer)
    <div class="pricing-card {{ $index === 0 ? 'first' : '' }} {{ $index === count($fitnessOffers)-1 ? 'last' : '' }}">
      <h3>{{ $offer->name }}</h3>
      <div class="price">
        <span class="amount">₱{{ $offer->price }}</span>
        <span class="duration">/{{ $offer->duration_days }} days</span>
      </div>
      <p class="summary">{{ $offer->description['summary'] ?? 'A great plan for your fitness journey.' }}</p>
      <ul>
        @if(isset($offer->description['features']))
          @foreach($offer->description['features'] as $feature)
            <li>
              <svg viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" />
              </svg>
              {{ $feature }}
            </li>
          @endforeach
        @endif
      </ul>
      <a href="#">Get started today</a>
    </div>
    @endforeach
  </div>
</div>
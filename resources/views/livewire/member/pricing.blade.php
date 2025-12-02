<div class="relative isolate bg-white dark:bg-gray-900 px-6 py-24 sm:py-32 lg:px-8">

  <!-- Background Glow -->
  <div aria-hidden="true" class="absolute inset-x-0 -top-3 -z-10 transform-gpu overflow-hidden px-36 blur-3xl">
    <div 
      class="mx-auto aspect-[1155/678] w-[72rem] bg-gradient-to-tr from-pink-300 to-indigo-400 opacity-30 dark:opacity-20"
      style="clip-path: polygon(74.1% 44.1%,100% 61.6%,97.5% 26.9%,85.5% 0.1%,80.7% 2%,72.5% 32.5%,60.2% 62.4%,52.4% 68.1%,47.5% 58.3%,45.2% 34.5%,27.5% 76.7%,0.1% 64.9%,17.9% 100%,27.6% 76.8%,76.1% 97.7%,74.1% 44.1%)">
    </div>
  </div>

  <!-- Title -->
  <div class="mx-auto max-w-4xl text-center">
    <h2 class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">Pricing</h2>
    <p class="mt-2 text-4xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-6xl">
      Choose the right plan for you
    </p>
  </div>

  <!-- Subtitle -->
  <p class="mx-auto mt-6 max-w-2xl text-center text-lg text-gray-600 dark:text-gray-300 sm:text-xl">
    Choose an affordable fitness plan packed with top-tier training programs, expert coaching, and facilities to help you hit your health goals.
  </p>

  <!-- Pricing Cards -->
  <div class="mx-auto mt-16 grid max-w-xl grid-cols-1 gap-6 sm:mt-20 lg:max-w-4xl lg:grid-cols-{{ count($fitnessOffers) }}">

    @foreach($fitnessOffers as $index => $offer)
    @php
      $isFirst = $index === 0;
      $isLast = $index === count($fitnessOffers) - 1;
    @endphp

    <div class="
      p-8 sm:p-10 rounded-3xl ring-1 
      ring-gray-900/10 dark:ring-gray-700/20 
      {{ $isFirst ? 'bg-white/60 dark:bg-gray-800/60' : 'bg-gray-900 dark:bg-gray-800' }}
      {{ $isLast ? 'shadow-xl relative' : '' }}
    ">

      <!-- Plan Name -->
      <h3 id="tier-{{ $offer->id }}"
        class="text-base font-semibold 
        {{ $isFirst ? 'text-indigo-600 dark:text-indigo-400' : 'text-indigo-400' }}">
        {{ $offer->name }}
      </h3>

      <!-- Price -->
      <p class="mt-4 flex items-baseline gap-x-2">
        <span class="text-5xl font-semibold 
          {{ $isFirst ? 'text-gray-900 dark:text-white' : 'text-white' }}">
          ₱{{ $offer->price }}
        </span>
        <span class="text-base text-gray-500 dark:text-gray-400">
          /{{ $offer->duration_days }} days
        </span>
      </p>

      <!-- Summary -->
      <p class="mt-6 text-base {{ $isFirst ? 'text-gray-600 dark:text-gray-300' : 'text-gray-300' }}">
        {{ $offer->description['summary'] ?? 'A great plan for your fitness journey.' }}
      </p>

      <!-- Features -->
      <ul class="mt-8 space-y-3 text-sm text-gray-600 dark:text-gray-300 sm:mt-10">
        @if(isset($offer->description['features']))
          @foreach($offer->description['features'] as $feature)
          <li class="flex gap-x-3">
            <svg viewBox="0 0 20 20" fill="currentColor" class="h-6 w-5 flex-none 
              {{ $isFirst ? 'text-indigo-600 dark:text-indigo-400' : 'text-indigo-400' }}">
              <path fill-rule="evenodd" clip-rule="evenodd"
                d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" />
            </svg>
            {{ $feature }}
          </li>
          @endforeach
        @endif
      </ul>

      <!-- Button -->
      <a href="#"
        class="mt-8 block text-center rounded-md px-4 py-2.5 text-sm font-semibold sm:mt-10
        {{ $isFirst 
          ? 'text-indigo-600 dark:text-indigo-400 ring-1 ring-inset ring-indigo-200 hover:ring-indigo-300 focus:outline-indigo-600' 
          : 'bg-indigo-500 text-white hover:bg-indigo-400 focus:outline-indigo-500 shadow' }}">
        Get started today
      </a>

    </div>
    @endforeach
  </div>
</div>

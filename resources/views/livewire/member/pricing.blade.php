<div class="relative isolate bg-white dark:bg-gray-900 px-6 py-24 sm:py-32 lg:px-8">
  <div aria-hidden="true" class="absolute inset-x-0 -top-3 -z-10 transform-gpu overflow-hidden px-36 blur-3xl">
    <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" class="mx-auto aspect-1155/678 w-288.75 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 dark:opacity-20"></div>
  </div>
  <div class="mx-auto max-w-4xl text-center">
    <h2 class="text-base/7 font-semibold text-indigo-600 dark:text-indigo-400">Pricing</h2>
    <p class="mt-2 text-5xl font-semibold tracking-tight text-balance text-gray-900 dark:text-white sm:text-6xl">Choose the right plan for you</p>
  </div>
  <p class="mx-auto mt-6 max-w-2xl text-center text-lg font-medium text-pretty text-gray-600 dark:text-gray-300 sm:text-xl/8">Choose an affordable plan that's packed with the best features for engaging your audience, creating customer loyalty, and driving sales.</p>
  <div class="mx-auto mt-16 grid max-w-lg grid-cols-1 items-center gap-y-6 sm:mt-20 sm:gap-y-0 lg:max-w-4xl lg:grid-cols-{{ count($fitnessOffers) }}">
    @foreach($fitnessOffers as $index => $offer)
    <div class="rounded-3xl {{ $index === 0 ? 'rounded-t-3xl bg-white/60 dark:bg-gray-800/60' : 'bg-gray-900 dark:bg-gray-800' }} p-8 ring-1 ring-gray-900/10 dark:ring-gray-700/20 sm:mx-8 sm:rounded-b-none sm:p-10 {{ $index === 0 ? 'lg:mx-0 lg:rounded-tr-none lg:rounded-bl-3xl' : '' }} {{ $index === count($fitnessOffers) - 1 ? 'relative shadow-2xl' : '' }}">
      <h3 id="tier-{{ $offer->id }}" class="text-base/7 font-semibold {{ $index === 0 ? 'text-indigo-600 dark:text-indigo-400' : 'text-indigo-400' }}">{{ $offer->name }}</h3>
      <p class="mt-4 flex items-baseline gap-x-2">
        <span class="text-5xl font-semibold tracking-tight {{ $index === 0 ? 'text-gray-900 dark:text-white' : 'text-white' }}">${{ $offer->price }}</span>
        <span class="text-base {{ $index === 0 ? 'text-gray-500 dark:text-gray-400' : 'text-gray-400' }}">/{{ $offer->duration_days }} days</span>
      </p>
      <p class="mt-6 text-base/7 {{ $index === 0 ? 'text-gray-600 dark:text-gray-300' : 'text-gray-300' }}">{{ $offer->description['summary'] ?? 'A great plan for your fitness journey.' }}</p>
      <ul role="list" class="mt-8 space-y-3 text-sm/6 {{ $index === 0 ? 'text-gray-600 dark:text-gray-300' : 'text-gray-300' }} sm:mt-10">
        @if(isset($offer->description['features']) && is_array($offer->description['features']))
          @foreach($offer->description['features'] as $feature)
          <li class="flex gap-x-3">
            <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="h-6 w-5 flex-none {{ $index === 0 ? 'text-indigo-600 dark:text-indigo-400' : 'text-indigo-400' }}">
              <path d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" fill-rule="evenodd" />
            </svg>
            {{ $feature }}
          </li>
          @endforeach
        @endif
      </ul>
      <a href="#" aria-describedby="tier-{{ $offer->id }}" class="mt-8 block rounded-md px-3.5 py-2.5 text-center text-sm font-semibold {{ $index === 0 ? 'text-indigo-600 dark:text-indigo-400 inset-ring inset-ring-indigo-200 hover:inset-ring-indigo-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600' : 'bg-indigo-500 text-white shadow-xs hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500' }} sm:mt-10">Get started today</a>
    </div>
    @endforeach
  </div>
</div>

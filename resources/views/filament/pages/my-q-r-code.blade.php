<x-filament-panels::page>
    {{-- Page content --}}
    @livewire('member.q-r-code.index')
    @livewireScripts()
    <style>
        /* Container */
        .pricing-section {
            position: relative;
            isolation: isolate;
            background-color: #fff;
            padding: 6rem 1.5rem;
        }

        @media (min-width: 640px) {
            .pricing-section {
                padding-top: 8rem;
                padding-bottom: 8rem;
            }
        }

        @media (min-width: 1024px) {
            .pricing-section {
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }

        /* Background Glow */
        .pricing-bg {
            position: absolute;
            inset-inline: 0;
            top: -0.75rem;
            z-index: -10;
            overflow: hidden;
            padding-inline: 9rem;
            filter: blur(3rem);
            transform: translateZ(0);
        }

        .pricing-bg-inner {
            margin-inline: auto;
            aspect-ratio: 1155 / 678;
            width: 72rem;
            background: linear-gradient(to top right, #f9a8d4, #818cf8);
            /* pink-300 to indigo-400 */
            opacity: 0.3;
            clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%,
                    72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%,
                    27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%);
        }

        /* Text Centering */
        .pricing-title,
        .pricing-subtitle {
            margin-inline: auto;
            text-align: center;
        }

        .pricing-title h2 {
            font-size: 0.875rem;
            font-weight: 600;
            color: #4f46e5;
            /* indigo-600 */
            margin: 0;
        }

        .pricing-title p {
            margin-top: 0.5rem;
            font-size: 2.25rem;
            font-weight: 700;
            line-height: 1.2;
            color: #111827;
            /* gray-900 */
        }

        @media (min-width: 640px) {
            .pricing-title p {
                font-size: 3.75rem;
            }
        }

        .pricing-subtitle {
            margin-top: 1.5rem;
            max-width: 42rem;
            font-size: 1.125rem;
            color: #4b5563;
            /* gray-600 */
        }

        @media (min-width: 640px) {
            .pricing-subtitle {
                font-size: 1.25rem;
            }
        }

        /* Pricing Cards Grid */
        .pricing-cards {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            margin: 4rem auto 0;
            max-width: 36rem;
        }

        @media (min-width: 640px) {
            .pricing-cards {
                margin-top: 5rem;
            }
        }

        @media (min-width: 1024px) {
            .pricing-cards {
                max-width: 64rem;
                grid-template-columns: repeat(var(--cards-count, 3), 1fr);
            }
        }

        /* Card Styles */
        .pricing-card {
            padding: 2rem;
            border-radius: 2rem;
            border: 1px solid rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            background-color: #09090B;
            /* default dark card */
            color: #fff;
        }

        .pricing-card.first {
            background-color: #FAFAFA;
            border-color: rgba(55, 65, 81, 0.2);
            color: #1A1A1AFF;
        }

        .pricing-card.last {
            position: relative;
        }

        .pricing-card h3 {
            font-size: 1rem;
            font-weight: 600;
            margin: 0;
            color: #818cf8;
            /* indigo-400 */
        }

        .pricing-card.first h3 {
            color: #4f46e5;
            /* indigo-600 */
        }

        /* Price */
        .pricing-card .price {
            margin-top: 1rem;
            display: flex;
            align-items: baseline;
            gap: 0.5rem;
        }

        .price .amount {
            font-size: 3rem;
            font-weight: 600;
            color: #fff;
        }

        .pricing-card.first .price .amount {
            color: #0F0F0FFF;
        }

        .price .duration {
            font-size: 1rem;
            color: #9ca3af;
            /* gray-400 */
        }

        /* Summary */
        .pricing-card .summary {
            margin-top: 1.5rem;
            font-size: 1rem;
            color: #d1d5db;
            /* gray-300 */
        }

        .pricing-card.first .summary {
            color: #181818FF;
            /* gray-600 */
        }

        /* Features List */
        .pricing-card ul {
            margin-top: 2rem;
            list-style: none;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            font-size: 0.875rem;
            color: #d1d5db;
            /* gray-300 */
        }

        .pricing-card.first ul {
            color: #4b5563;
            /* gray-600 */
        }

        .pricing-card ul li {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .pricing-card ul li svg {
            flex-shrink: 0;
            width: 1.25rem;
            height: 1.5rem;
            color: #818cf8;
        }

        .pricing-card.first ul li svg {
            color: #4f46e5;
        }

        /* Button */
        .pricing-card a {
            margin-top: 2rem;
            display: block;
            text-align: center;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.625rem 1rem;
            border-radius: 0.375rem;
            text-decoration: none;
        }

        .pricing-card.first a {
            color: #4f46e5;
            border: 1px inset rgba(191, 219, 254, 0.4);
        }

        .pricing-card.first a:hover {
            border-color: rgba(191, 219, 254, 0.6);
        }

        .pricing-card:not(.first) a {
            background-color: #6366f1;
            color: #fff;
        }

        .pricing-card:not(.first) a:hover {
            background-color: #818cf8;
        }

        /* Dark Mode */
        .dark .pricing-section {
            background-color: #18181B;
        }

        .dark .pricing-title h2 {
            color: #818cf8;
        }

        .dark .pricing-title p {
            color: #fff;
        }

        .dark .pricing-subtitle {
            color: #d1d5db;
        }

        .dark .pricing-card.first {
            background-color: rgba(55, 65, 81, 0.6);
            color: #fff;
        }

        .dark .pricing-card {
            background-color: #09090B;
            color: #fff;
            border-color: rgba(55, 65, 81, 0.2);
        }

        .dark .pricing-card.first h3 {
            color: #818cf8;
        }

        .dark .pricing-card h3 {
            color: #818cf8;
        }

        .dark .pricing-card.first .summary {
            color: #d1d5db;
        }

        .dark .pricing-card .summary {
            color: #d1d5db;
        }

        .dark .pricing-card ul {
            color: #d1d5db;
        }

        .dark .pricing-card.first ul {
            color: #d1d5db;
        }

        .dark .pricing-card ul li svg {
            color: #818cf8;
        }

        .dark .pricing-card.first ul li svg {
            color: #818cf8;
        }

        .dark .pricing-card.first .price .amount {
            color: #D3D3D3FF;
        }
        .dark .pricing-card.first a {
            color: #818cf8;
            border-color: rgba(147, 197, 253, 0.4);
        }
        .fi-loading-indicator{
            display: none !important;
        }
    </style>
</x-filament-panels::page>

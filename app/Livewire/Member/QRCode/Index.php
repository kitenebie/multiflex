<?php

namespace App\Livewire\Member\QRCode;

use Livewire\Component;
use App\Models\FitnessOffer;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public function render()
    {
        $activeSubscription = Auth::user()->subscriptions()->where('status', 'active')->first();
        $fitness_offer_id = $activeSubscription ? $activeSubscription->fitness_offer_id : null;
        $fitnessOffers = $fitness_offer_id ? FitnessOffer::where('id', $fitness_offer_id)->get() : collect();
        return view('livewire.member.q-r-code.index', [
            'fitnessOffers' => $fitnessOffers,
        ]);
    }
}

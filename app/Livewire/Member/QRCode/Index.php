<?php

namespace App\Livewire\Member\QRCode;

use Livewire\Component;
use App\Models\FitnessOffer;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public function render()
    {
        $fitnessOffers = FitnessOffer::where('id', Auth::user()->subscriptions->fitness_offer_id)->get();
        return view('livewire.member.q-r-code.index', [
            'fitnessOffers' => $fitnessOffers,
        ]);
    }
}

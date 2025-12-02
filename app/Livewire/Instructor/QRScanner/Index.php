<?php

namespace App\Livewire\Instructor\QRScanner;

use Livewire\Component;
use App\Models\FitnessOffer;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public function render()
    {
        $fitnessOffers = FitnessOffer::where('id', Auth::user()->subscriptions->fitness_offer_id)->get();
        return view('livewire.instructor.q-r-scanner.index', [
            'fitnessOffers' => $fitnessOffers,
        ]);
    }
}

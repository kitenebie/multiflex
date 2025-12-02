<?php

namespace App\Livewire\Member;

use App\Models\FitnessOffer;
use Livewire\Component;

class Pricing extends Component
{
    public function render()
    {
        $fitnessOffers = FitnessOffer::all();

        return view('livewire.member.pricing', [
            'fitnessOffers' => $fitnessOffers,
        ]);
    }
}

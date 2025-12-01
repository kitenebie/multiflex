<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RegistrationController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'member_type' => 'required|in:0,1',
                'firstname' => 'required|string|max:255',
                'middlename' => 'nullable|string|max:255',
                'lastname' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string',
            ]);

            $name = trim($request->firstname . ' ' . ($request->middlename ? $request->middlename . ' ' : '') . $request->lastname);

            // Determine role based on member_type
            $role = $request->member_type == 0 ? 'member' : 'coach';

            $user = \App\Models\User::create([
                'name' => $name,
                'email' => $request->email,
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                'role' => $role, // dynamically set
                'status' => 'pending',
                'membership' => $request->member_type,
            ]);

            // Assign Spatie role
            $user->assignRole($role);

            // Generate QR code
            $user->update([
                'qr_code' => bcrypt($user->id),
            ]);
            Auth::login($user);
            if (Auth::user()->role == 'admin') {
                return redirect('/public/app');
            }
            return redirect('/#pricingSection')->with('success', 'Registration successful!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Registration failed: ' . $e->getMessage())->withInput();
        }
    }


    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = User::where('email', $request->email)->first();
                Auth::login($user);
                $user->update([
                    'qr_code' => bcrypt($user->id . now()),
                ]);
                $user->save();
                return redirect('/public/app');
            } else {
                return redirect()->back()->with('error', 'Invalid credentials.')->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Login failed: ' . $e->getMessage())->withInput();
        }
    }

    public function subscribe(Request $request)
    {
        try {
            $request->validate([
                'offer_id' => 'required|exists:fitness_offers,id',
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'subscription_months' => 'required|integer|min:1',
                'reference' => 'required|string',
                'start_date' => 'required|date|after_or_equal:today',
                'proof_of_payment' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            ]);

            $offer = \App\Models\FitnessOffer::find($request->offer_id);
            $baseDays = $offer->duration_days;
            $multiplier = $request->subscription_months / $baseDays;
            $amount = $offer->price * $multiplier;

            // Store the proof of payment file
            $proofPath = $request->file('proof_of_payment')->store('proofs', 'public');

            // Create subscription
            $subscription = Subscription::create([
                'user_id' => Auth::user()->id,
                'fitness_offer_id' => $request->offer_id,
                'status' => 'pending',
                'start_date' => $request->start_date,
                'end_date' => \Carbon\Carbon::parse($request->start_date)->addDays((int)$request->subscription_months),
                'coach_id' => null
            ]);

            // Create subscription transaction
            SubscriptionTransaction::create([
                'subscription_id' => $subscription->id,
                'amount' => $amount,
                'payment_method' => 'upload', // or whatever
                'reference_no' => $request->reference,
                'paid_at' => now(),
                'proof_of_payment' => $proofPath,
            ]);
            Auth::login(User::where('email', $request->email)->first());
            return redirect('/public/app/subscriptions');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Subscription failed: ' . $e->getMessage())->withInput();
        }
    }
}

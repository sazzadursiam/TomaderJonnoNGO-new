<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Stat;
use App\Models\Story;
use App\Models\TeamMember;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('pages.home', [
            'programs' => Program::query()->published()->get(),
            'stats' => Stat::query()->ordered()->get(),
            'stories' => Story::query()->published()->take(3)->get(),
            'donationTiers' => config('donation.tiers'),
        ]);
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function team(): View
    {
        return view('pages.team', [
            'members' => TeamMember::query()->published()->get(),
        ]);
    }

    public function donate(): View
    {
        return view('pages.donate', [
            'donationTiers' => config('donation.tiers'),
        ]);
    }
}

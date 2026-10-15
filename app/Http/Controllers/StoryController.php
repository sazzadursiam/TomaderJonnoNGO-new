<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Contracts\View\View;

class StoryController extends Controller
{
    public function index(): View
    {
        return view('stories.index', [
            'stories' => Story::query()->published()->paginate(9),
        ]);
    }

    public function show(Story $story): View
    {
        abort_unless(
            $story->is_published && ($story->published_at === null || $story->published_at->isPast()),
            404,
        );

        return view('stories.show', [
            'story' => $story,
            'moreStories' => Story::query()->published()->whereKeyNot($story->getKey())->take(3)->get(),
        ]);
    }
}

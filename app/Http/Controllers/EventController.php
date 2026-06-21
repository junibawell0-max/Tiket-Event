<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::where('status', 'published')->orderBy('date', 'asc');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Category filter (simplified helper)
        if ($request->filled('category')) {
            $category = $request->input('category');
            if ($category === 'music') {
                $query->where(function($q) {
                    $q->where('title', 'like', '%symphony%')
                      ->orWhere('title', 'like', '%concert%')
                      ->orWhere('description', 'like', '%musik%');
                });
            } elseif ($category === 'tech') {
                $query->where(function($q) {
                    $q->where('title', 'like', '%tech%')
                      ->orWhere('title', 'like', '%summit%')
                      ->orWhere('description', 'like', '%teknologi%');
                });
            } elseif ($category === 'art') {
                $query->where(function($q) {
                    $q->where('title', 'like', '%art%')
                      ->orWhere('title', 'like', '%exhibition%')
                      ->orWhere('description', 'like', '%pameran%');
                });
            } elseif ($category === 'esports') {
                $query->where(function($q) {
                    $q->where('title', 'like', '%esports%')
                      ->orWhere('title', 'like', '%gaming%')
                      ->orWhere('description', 'like', '%turnamen%');
                });
            }
        }

        $events = $query->get();
        $featuredEvent = Event::where('status', 'published')->where('is_featured', true)->first() ?: $events->first();

        return view('home', compact('events', 'featuredEvent'));
    }

    public function show($slug)
    {
        $event = Event::where('slug', $slug)
            ->where('status', 'published')
            ->with('ticketCategories')
            ->firstOrFail();

        return view('event.show', compact('event'));
    }
}

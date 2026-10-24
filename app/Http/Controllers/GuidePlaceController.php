<?php

namespace App\Http\Controllers;

use App\Models\GuidePlace;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class GuidePlaceController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'in:'.implode(',', GuidePlace::TYPES)],
            'city' => ['nullable', 'string', 'max:100'],
        ]);

        $search = trim($filters['q'] ?? '');
        $type = $filters['type'] ?? '';
        $city = trim($filters['city'] ?? '');
        $places = GuidePlace::query()
            ->published()
            ->when($type !== '', fn (Builder $query): Builder => $query->where('type', $type))
            ->when($city !== '', fn (Builder $query): Builder => $query->where('city', $city))
            ->when($search !== '', function (Builder $query) use ($search): void {
                $like = '%'.addcslashes($search, '\\%_').'%';

                $query->where(function (Builder $matches) use ($like): void {
                    $matches->where('name', 'like', $like)
                        ->orWhere('address', 'like', $like)
                        ->orWhere('near_campus', 'like', $like)
                        ->orWhere('description', 'like', $like);
                });
            })
            ->orderByDesc('is_verified')
            ->orderBy('name')
            ->orderBy('id')
            ->paginate(12)
            ->withQueryString();

        $cities = GuidePlace::query()
            ->published()
            ->select('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        return view('pages.guide-places', [
            'places' => $places,
            'cities' => $cities,
            'types' => GuidePlace::TYPE_LABELS,
            'search' => $search,
            'type' => $type,
            'city' => $city,
        ]);
    }

    public function show(GuidePlace $guidePlace): View
    {
        abort_unless($guidePlace->is_published, 404);

        $relatedPlaces = GuidePlace::query()
            ->published()
            ->where('id', '!=', $guidePlace->id)
            ->where('type', $guidePlace->type)
            ->where('city', $guidePlace->city)
            ->orderByDesc('is_verified')
            ->orderBy('name')
            ->take(3)
            ->get();

        return view('pages.guide-place', [
            'place' => $guidePlace,
            'relatedPlaces' => $relatedPlaces,
            'typeLabel' => GuidePlace::TYPE_LABELS[$guidePlace->type] ?? $guidePlace->type,
        ]);
    }
}

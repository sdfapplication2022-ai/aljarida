<?php

namespace App\Http\Controllers;

use App\Models\{About, Article, ArticleLike, Blog, Category, Comment, ContactMessage, Service, Subscriber, Tag, TeamMember, Testimonial, User, Poll, PollVote};
use App\Services\Widgets;
use App\Support\AdminNotifier;
use App\Support\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SiteController extends Controller
{
    public function home(Request $request)
    {
        // Pas de cache des modèles Eloquent : Laravel 12 refuse de les désérialiser (voir cache.serializable_classes)
        $data = (function () {
            $base = fn () => Article::published()->with(['category', 'user']);

            // À la une : articles marqués "à la une", complétés par les plus récents
            $featured = $base()->where('is_featured', true)->latest('published_at')->take(5)->get();
            if ($featured->count() < 5) {
                $fill = $base()->whereNotIn('id', $featured->pluck('id'))
                    ->latest('published_at')->take(5 - $featured->count())->get();
                $featured = $featured->concat($fill)->values();
            }

            // Bandeau « عاجل »
            $breaking = Article::published()->latest('published_at')
                ->take(8)->get(['id', 'title', 'slug', 'published_at']);

            // Les plus lus (30 derniers jours, sinon depuis toujours)
            $mostRead = Article::published()->with('category')
                ->where('published_at', '>=', now()->subDays(30))
                ->orderByDesc('views')->take(5)->get();
            if ($mostRead->isEmpty()) {
                $mostRead = Article::published()->with('category')->orderByDesc('views')->take(5)->get();
            }

            // Dernières actualités (hors "à la une")
            $latest = $base()->whereNotIn('id', $featured->pluck('id'))
                ->latest('published_at')->take(8)->get();
            if ($latest->isEmpty()) { // petit site : tous les articles sont déjà en "à la une"
                $latest = $base()->latest('published_at')->take(8)->get();
            }

            // Sections par rubrique : 4 rubriques les plus actives, 4 articles chacune
            $sections = Category::orderBy('name')->get()
                ->each(fn ($cat) => $cat->setRelation(
                    'latestArticles',
                    $cat->articles()->published()->with('user')->latest('published_at')->take(4)->get()
                ))
                ->filter(fn ($cat) => $cat->latestArticles->isNotEmpty())
                ->sortByDesc(fn ($cat) => optional($cat->latestArticles->first()->published_at)->timestamp ?? 0)
                ->take(4)->values();

            return compact('featured', 'breaking', 'mostRead', 'latest', 'sections') + [
                // Sections du site conservées
                'about'        => About::first(),
                'services'     => Service::all(),
                'testimonials' => Testimonial::latest()->take(6)->get(),
                'team'         => TeamMember::all(),
                'blogs'        => Blog::latest('published_at')->take(3)->get(),
            ];
        })();

        return view('pages.home', $data + $this->widgetsData($request));
    }

    /** Sondage du jour + météo / prière / change (le cache est géré par App\Services\Widgets). */
    private function widgetsData(Request $request): array
    {
        $poll = Poll::current()->with('options')->first();
        $pollVote = null;

        if ($poll) {
            $user = $request->user();
            $token = Visitor::existing($request);
            if ($user || $token) {
                $pollVote = PollVote::where('poll_id', $poll->id)
                    ->where($user ? ['user_id' => $user->id] : ['visitor_token' => $token])
                    ->first();
            }
        }

        return [
            'widgets'  => app(Widgets::class)->all(),
            'poll'     => $poll,
            'pollVote' => $pollVote,
        ];
    }

    public function category(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $articles = $category->articles()->published()->with('user')->latest('published_at')->paginate(9);
        return view('pages.category', compact('category', 'articles'));
    }

    public function article(Request $request, string $slug)
    {
        $article = Article::published()->with(['category', 'user', 'tags'])->withCount('likes')
            ->where('slug', $slug)->firstOrFail();
        $article->increment('views');

        $user  = $request->user();
        $token = Visitor::existing($request);

        // Commentaires approuvés + ceux du visiteur lui-même (même en attente de modération)
        $comments = $article->comments()
            ->where(function ($q) use ($token, $user) {
                $q->where('is_approved', true);
                if ($token) { $q->orWhere('visitor_token', $token); }
                if ($user)  { $q->orWhere('user_id', $user->id); }
            })
            ->with('user')->latest()->get();

        // Ce visiteur a-t-il déjà aimé cet article ?
        $liked = ($user || $token) && ArticleLike::where('article_id', $article->id)
            ->where($user ? ['user_id' => $user->id] : ['visitor_token' => $token])
            ->exists();

        // Pré-remplit nom / e-mail avec ceux de son dernier commentaire
        $guest = (! $user && $token)
            ? Comment::where('visitor_token', $token)->whereNotNull('guest_name')->latest()->first(['guest_name', 'guest_email'])
            : null;

        // Articles similaires : même rubrique OU tags en commun, ceux qui partagent le plus de tags d'abord
        $tagIds = $article->tags->pluck('id')->all();
        $related = Article::published()->where('id', '!=', $article->id)
            ->where(function ($q) use ($article, $tagIds) {
                $q->where('category_id', $article->category_id);
                if ($tagIds) {
                    $q->orWhereHas('tags', fn ($t) => $t->whereIn('tags.id', $tagIds));
                }
            })
            ->withCount(['tags as shared_tags' => fn ($t) => $t->whereIn('tags.id', $tagIds ?: [0])])
            ->orderByDesc('shared_tags')->latest('published_at')->take(3)->get();

        // Article précédent / suivant (ordre de création)
        $older = Article::published()->where('id', '<', $article->id)->orderByDesc('id')->first(['id', 'title', 'slug']);
        $newer = Article::published()->where('id', '>', $article->id)->orderBy('id')->first(['id', 'title', 'slug']);

        $mostRead = Article::published()->with('category')
            ->where('id', '!=', $article->id)->orderByDesc('views')->take(5)->get();

        return view('pages.article', [
            'article' => $article, 'comments' => $comments, 'related' => $related,
            'liked' => $liked, 'guest' => $guest, 'visitorToken' => $token,
        ] + $this->widgetsData($request) + compact('mostRead', 'older', 'newer'));
    }

    public function blog(int $id)
    {
        return view('pages.blog', ['blog' => Blog::findOrFail($id)]);
    }

    public function search(Request $request)
    {
        $q = Str::limit(trim((string) $request->query('q', '')), 100, '');

        $articles = Article::published()->with(['category', 'user'])
            ->when($q !== '', fn ($query) => $query->search($q), fn ($query) => $query->whereRaw('1 = 0'))
            ->paginate(9)->withQueryString();

        return view('pages.search', compact('q', 'articles'));
    }

    /** Suggestions instantanées (JSON) pour la barre de recherche. */
    public function suggest(Request $request)
    {
        $q = Str::limit(trim((string) $request->query('q', '')), 100, '');
        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $like = '%'.addcslashes($q, '\\%_').'%';

        $items = Article::published()->with('category:id,name')
            ->where('title', 'like', $like)
            ->latest('published_at')->take(6)->get(['id', 'title', 'slug', 'category_id']);

        return response()->json($items->map(fn ($a) => [
            'title'    => $a->title,
            'url'      => route('article', $a->slug),
            'category' => $a->category?->name,
        ])->values());
    }

    public function tag(string $slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();
        $articles = $tag->articles()->published()->with(['category', 'user'])
            ->latest('published_at')->paginate(9);

        return view('pages.tag', compact('tag', 'articles'));
    }

    public function author(User $user)
    {
        abort_unless($user->isStaff(), 404);
        $articles = $user->articles()->published()->with('category')
            ->latest('published_at')->paginate(9);

        return view('pages.author', compact('user', 'articles'));
    }

    // ------------------------------------------------------------------
    //  Commentaires et likes (avec ou sans compte)
    // ------------------------------------------------------------------

    public function comment(Request $request, Article $article)
    {
        $user = $request->user();

        $rules = [
            'content' => 'required|string|min:3|max:2000',
            'website' => 'prohibited', // honeypot anti-robots
        ];
        if (! $user) {
            $rules['guest_name']  = 'required|string|min:2|max:100';
            $rules['guest_email'] = 'required|email|max:255';
        }
        $data = $request->validate($rules);

        $comment = Comment::create([
            'content'       => strip_tags($data['content']),
            'article_id'    => $article->id,
            'is_approved'   => false,
            'guest_name'    => $user ? null : strip_tags($data['guest_name']),
            'guest_email'   => $user ? null : $data['guest_email'],
            'visitor_token' => Visitor::token($request),
        ]);

        AdminNotifier::comment($comment->load('article', 'user'));

        return redirect(route('article', $article->slug).'#comments')
            ->with('success', 'شكرًا! تم استلام تعليقك وسيظهر للجميع بعد مراجعته.');
    }

    public function destroyComment(Request $request, Comment $comment)
    {
        abort_unless($comment->isOwnedBy($request->user(), Visitor::existing($request)), 403);

        $slug = $comment->article?->slug;
        $comment->delete();

        return redirect($slug ? route('article', $slug).'#comments' : route('home'))
            ->with('success', 'تم حذف تعليقك.');
    }

    /** Bascule : un clic aime, un second clic retire le like. */
    public function like(Request $request, Article $article)
    {
        $user = $request->user();
        $who  = $user ? ['user_id' => $user->id] : ['visitor_token' => Visitor::token($request)];

        $existing = ArticleLike::where('article_id', $article->id)->where($who)->first();

        if ($existing) {
            $existing->delete();
        } else {
            ArticleLike::firstOrCreate(['article_id' => $article->id] + $who, ['ip' => $request->ip()]);
        }

        return back();
    }

    public function contactPage() { return view('pages.contact'); }

    public function contact(Request $request)
    {
        $msg = ContactMessage::create($request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'service_type' => 'nullable|string|max:100',
            'message' => 'required|string|max:5000',
        ]));

        AdminNotifier::contactMessage($msg);
        return back()->with('success', 'تم إرسال رسالتك بنجاح، سنرد عليك قريبًا.');
    }

    public function subscribePage() { return view('pages.subscribe'); }

    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'email'   => 'required|email|max:255',
            'website' => 'prohibited', // honeypot
        ]);
        Subscriber::updateOrCreate(['email' => $data['email']], ['is_active' => true]);
        return back()->with('success', 'تم اشتراكك في النشرة البريدية. شكرًا لك!');
    }

    public function team() { return view('pages.team', ['team' => TeamMember::all(), 'about' => About::first()]); }
}

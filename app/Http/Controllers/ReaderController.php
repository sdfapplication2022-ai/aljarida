<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ReaderBookmark;
use App\Support\Visitor;
use Illuminate\Http\Request;

class ReaderController extends Controller
{
    public function saved(Request $request)
    {
        $readerKey = $this->readerKey($request);
        $articleIds = $readerKey
            ? ReaderBookmark::where('reader_key', $readerKey)->latest('created_at')->pluck('article_id')
            : collect();

        $articles = Article::published()->with(['category', 'user'])
            ->whereIn('id', $articleIds)->latest('published_at')->paginate(9);

        return view('pages.saved-articles', compact('articles'));
    }

    public function toggleSaved(Request $request, Article $article)
    {
        abort_unless(Article::published()->whereKey($article->id)->exists(), 404);

        $readerKey = $this->readerKey($request, true);
        $bookmark = ReaderBookmark::where('reader_key', $readerKey)
            ->where('article_id', $article->id)->first();

        if ($bookmark) {
            $bookmark->delete();
            $message = 'تمت إزالة المقال من قائمة القراءة لاحقًا.';
        } else {
            ReaderBookmark::create(['reader_key' => $readerKey, 'article_id' => $article->id]);
            $message = 'أضيف المقال إلى قائمة القراءة لاحقًا.';
        }

        return back()->with('success', $message);
    }

    private function readerKey(Request $request, bool $createVisitor = false): ?string
    {
        if ($request->user()) {
            return 'user:'.$request->user()->getAuthIdentifier();
        }

        $token = $createVisitor ? Visitor::token($request) : Visitor::existing($request);

        return $token ? 'visitor:'.$token : null;
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Article, Comment, ContactMessage, Subscriber, User};

class DashboardController extends Controller
{
    public function __invoke()
    {
        return $this->index();
    }

    public function index()
    {
        return view('admin.dashboard', [
            'stats' => [
                ['المقالات', Article::count(), 'articles', 'fa-newspaper'],
                ['تعليقات بانتظار الموافقة', Comment::where('is_approved', false)->count(), 'comments', 'fa-comments'],
                ['المشتركون', Subscriber::count(), 'subscribers', 'fa-envelope'],
                ['رسائل الاتصال', ContactMessage::count(), 'messages', 'fa-inbox'],
            ],
            'latestArticles' => Article::latest()->take(5)->get(),
            'latestMessages' => ContactMessage::latest()->take(5)->get(),
        ]);
    }
}

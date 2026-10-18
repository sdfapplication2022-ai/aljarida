<?php

namespace App\Support;

use App\Models\{About, Poll, Article, Blog, Category, Comment, ContactMessage, Service, Subscriber, Tag, TeamMember, Testimonial, User};

/**
 * Définition déclarative de chaque CRUD de l'administration.
 * columns : colonnes de la liste  |  fields : champs du formulaire
 * Dans les règles, {id} est remplacé par l'id de l'enregistrement édité.
 */
class AdminResources
{
    public static function all(): array
    {
        $img = 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096';

        return [
                'articles' => [
                    'model'    => \App\Models\Article::class,
                    'title'    => 'المقالات',
                    'single'   => 'مقال',
                    'icon'     => 'fa-newspaper',
                    'with'     => ['category', 'user'],
                    'search'   => ['title', 'content', 'excerpt'],
                    'slugFrom' => 'title',
                    'formView' => 'admin.articles.form',   // 👈 vue pro dédiée

                    // ⚠️ TOUS les champs doivent figurer ici pour que save() les récupère
                    'fields' => [
                        ['title',            'العنوان',            'text',     'required|string|max:255'],
                        ['slug',             'الرابط',             'text',     'nullable|string|max:255'],
                        ['excerpt',          'مقتطف',              'textarea', 'nullable|string|max:500'],
                        ['content',          'المحتوى',            'richtext', 'required|string'],
                        ['meta_title',       'عنوان SEO',          'text',     'nullable|string|max:70'],
                        ['meta_description', 'وصف SEO',            'textarea', 'nullable|string|max:170'],
                        ['image',            'الصورة البارزة',     'file',     'nullable|image|max:8192'],
                        ['video_url',        'فيديو',              'file',     'nullable|file|mimes:mp4|max:51200'],
                        ['category_id',      'التصنيف',            'select',   'nullable|exists:categories,id',
                            ['source' => \App\Models\Category::class, 'label' => 'name']],
                        ['published_at',     'تاريخ النشر',        'datetime', 'nullable|date'],
                        ['is_published',     'منشور',              'checkbox', 'nullable|boolean'],
                        ['is_featured',      'مميز',               'checkbox', 'nullable|boolean'],
                    ],

                    'columns' => [
                        'title'     => 'العنوان',
                        'is_published' => 'الحالة',
                        'published_at' => 'تاريخ النشر',
                    ],
                ],
            'categories' => [
                'model' => Category::class, 'title' => 'الأقسام', 'single' => 'قسم', 'icon' => 'fa-folder', 'slugFrom' => 'name',
                'columns' => ['id' => '#', 'name' => 'الاسم', 'slug' => 'الرابط'], 'search' => ['name'],
                'fields' => [
                    ['name', 'الاسم', 'text', 'required|string|max:255'],
                    ['slug', 'الرابط (اختياري)', 'text', 'nullable|string|max:255|unique:categories,slug,{id}'],
                    ['description', 'الوصف', 'richtext', 'nullable|string'],
                ],
            ],
            'tags' => [
                'model' => Tag::class, 'title' => 'الوسوم', 'single' => 'وسم', 'icon' => 'fa-tags', 'slugFrom' => 'name',
                'columns' => ['id' => '#', 'name' => 'الاسم', 'slug' => 'الرابط'], 'search' => ['name'],
                'fields' => [
                    ['name', 'الاسم', 'text', 'required|string|max:255'],
                    ['slug', 'الرابط (اختياري)', 'text', 'nullable|string|max:255|unique:tags,slug,{id}'],
                ],
            ],
            'blogs' => [
                'model' => Blog::class, 'title' => 'المدونة', 'single' => 'تدوينة', 'icon' => 'fa-pen',
                'columns' => ['id' => '#', 'title' => 'العنوان', 'author' => 'الكاتب', 'published_at' => 'التاريخ'], 'search' => ['title'],
                'fields' => [
                    ['title', 'العنوان', 'text', 'required|string|max:255'],
                    ['author', 'الكاتب', 'text', 'nullable|string|max:255'],
                    ['content', 'المحتوى', 'richtext', 'nullable|string'],
                    ['image', 'الصورة', 'file', $img],
                    ['published_at', 'تاريخ النشر', 'datetime', 'nullable|date'],
                ],
            ],
            'comments' => [
                'model' => Comment::class, 'title' => 'التعليقات', 'single' => 'تعليق', 'icon' => 'fa-comments', 'create' => false,
                'with' => ['article', 'user'], 'columns' => ['id' => '#', 'content' => 'التعليق', 'author_name' => 'الاسم', 'guest_email' => 'البريد',
              'article.title' => 'المقال', 'is_approved' => 'مقبول'], 'search' => ['content'],
                'fields' => [
                    ['content', 'التعليق', 'textarea', 'required|string'],
                    ['is_approved', 'مقبول', 'checkbox', 'boolean'],
                ],
            ],
            'services' => [
                'model' => Service::class, 'title' => 'الخدمات', 'single' => 'خدمة', 'icon' => 'fa-briefcase',
                'columns' => ['id' => '#', 'title' => 'العنوان', 'description' => 'الوصف'], 'search' => ['title'],
                'fields' => [
                    ['title', 'العنوان', 'text', 'required|string|max:255'],
                    ['icon', 'أيقونة (رابط أو مسار)', 'text', 'nullable|string|max:255'],
                    ['description', 'الوصف', 'richtext', 'nullable|string'],
                    ['photo', 'الصورة', 'file', $img],
                ],
            ],
            'testimonials' => [
                'model' => Testimonial::class, 'title' => 'آراء القراء', 'single' => 'رأي', 'icon' => 'fa-quote-right',
                'columns' => ['id' => '#', 'author_name' => 'الاسم', 'content' => 'الرأي', 'rating' => 'التقييم'], 'search' => ['author_name'],
                'fields' => [
                    ['author_name', 'الاسم', 'text', 'required|string|max:255'],
                    ['author_role', 'الصفة', 'text', 'nullable|string|max:255'],
                    ['content', 'الرأي', 'richtext', 'required|string'],
                    ['rating', 'التقييم (1-5)', 'number', 'required|integer|min:1|max:5'],
                ],
            ],
            'team' => [
                'model' => TeamMember::class, 'title' => 'فريق التحرير', 'single' => 'عضو', 'icon' => 'fa-users',
                'columns' => ['id' => '#', 'name' => 'الاسم', 'position' => 'المنصب'], 'search' => ['name'],
                'fields' => [
                    ['name', 'الاسم', 'text', 'required|string|max:255'],
                    ['position', 'المنصب', 'text', 'nullable|string|max:255'],
                    ['bio', 'نبذة', 'richtext', 'nullable|string'],
                    ['photo', 'الصورة', 'file', $img],
                    ['facebook', 'Facebook', 'text', 'nullable|url'],
                    ['twitter', 'Twitter', 'text', 'nullable|url'],
                    ['instagram', 'Instagram', 'text', 'nullable|url'],
                    ['linkedin', 'LinkedIn', 'text', 'nullable|url'],
                ],
            ],
            'about' => [
                'model' => About::class, 'title' => 'من نحن', 'single' => 'محتوى', 'icon' => 'fa-circle-info',
                'columns' => ['id' => '#', 'title' => 'العنوان', 'subtitle' => 'العنوان الفرعي'],
                'fields' => [
                    ['title', 'العنوان', 'text', 'required|string|max:255'],
                    ['subtitle', 'العنوان الفرعي', 'text', 'nullable|string|max:255'],
                    ['description', 'الوصف', 'richtext', 'nullable|string'],
                    ['main_image', 'الصورة الرئيسية', 'file', $img],
                    ['secondary_image', 'الصورة الثانوية', 'file', $img],
                    ['video_url', 'فيديو (mp4)', 'file', 'nullable|file|mimes:mp4|max:51200'],
                ],
            ],
            'subscribers' => [
                'model' => Subscriber::class, 'title' => 'المشتركون', 'single' => 'مشترك', 'icon' => 'fa-envelope',
                'columns' => ['id' => '#', 'email' => 'البريد', 'is_active' => 'نشط', 'created_at' => 'تاريخ الاشتراك'], 'search' => ['email'],
                'fields' => [
                    ['email', 'البريد الإلكتروني', 'email', 'required|email|unique:subscribers,email,{id}'],
                    ['is_active', 'نشط', 'checkbox', 'boolean'],
                ],
            ],
            'messages' => [
                'model' => ContactMessage::class, 'title' => 'رسائل الاتصال', 'single' => 'رسالة', 'icon' => 'fa-inbox', 'create' => false, 'edit' => false,
                'columns' => ['id' => '#', 'first_name' => 'الاسم', 'email' => 'البريد', 'service_type' => 'الخدمة', 'message' => 'الرسالة', 'created_at' => 'التاريخ'], 'search' => ['first_name', 'last_name', 'email'],
                'fields' => [],
            ],
            'polls' => [
                'model' => Poll::class, 'title' => 'استطلاعات الرأي', 'single' => 'استطلاع', 'icon' => 'fa-square-poll-vertical',
                'columns' => ['id' => '#', 'question' => 'السؤال', 'is_active' => 'نشط', 'closes_at' => 'ينتهي في', 'created_at' => 'التاريخ'],
                'search' => ['question'],
                'fields' => [
                    ['question', 'السؤال', 'text', 'required|string|max:255'],
                    ['options_text', 'الخيارات (خيار في كل سطر، من 2 إلى 8)', 'textarea', 'required|string|min:3'],
                    ['is_active', 'نشط', 'checkbox', 'boolean'],
                    ['closes_at', 'ينتهي في (اختياري)', 'datetime', 'nullable|date'],
                ],
            ],
            'users' => [
                'model' => User::class, 'title' => 'المستخدمون', 'single' => 'مستخدم', 'icon' => 'fa-user-gear', 'adminOnly' => true,
                'columns' => ['id' => '#', 'name' => 'الاسم', 'email' => 'البريد', 'role' => 'الدور'], 'search' => ['name', 'email'],
                'fields' => [
                    ['name', 'الاسم', 'text', 'required|string|max:255'],
                    ['email', 'البريد الإلكتروني', 'email', 'required|email|unique:users,email,{id}'],
                    ['password', 'كلمة المرور (اتركها فارغة للإبقاء)', 'password', 'nullable|string|min:8'],
                    ['role', 'الدور', 'select', 'required|in:admin,editor,user', ['choices' => ['admin' => 'مدير', 'editor' => 'محرر', 'user' => 'مستخدم']]],
                    ['bio', 'نبذة', 'richtext', 'nullable|string'],
                    ['photo', 'الصورة', 'file', $img],
                ],
            ],
        ];
    }
}

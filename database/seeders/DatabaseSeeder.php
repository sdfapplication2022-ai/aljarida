<?php

namespace Database\Seeders;

use App\Models\{About, Article, Blog, Category, Service, TeamMember, Testimonial, User};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(['email' => 'admin@aljarida.tn'], [
            'name' => 'Administrateur', 'password' => 'password', 'role' => 'admin',
        ]);

        foreach ([['أخبار','news'],['تعليم','education'],['مرأة','women'],['ثقافة','culture']] as [$name, $slug]) {
            Category::firstOrCreate(['slug' => $slug], ['name' => $name]);
        }

        About::firstOrCreate([], [
            'title' => 'نقدم الأخبار بحيادية ودقة متناهية',
            'subtitle' => 'من نحن',
            'description' => 'نحن منصة إخبارية تواكب الأحداث لحظة بلحظة، ملتزمون بتقديم محتوى موثوق يعكس الحقيقة بشفافية ومصداقية.',
            'video_url' => null,
        ]);

        if (! Service::count()) {
            foreach ([
                ['التقارير الحصرية','تحليلات معمقة وتقارير ميدانية حصرية تضعك في قلب الأحداث.'],
                ['التحقيقات الصحفية','تحقيقات معمقة تكشف الحقائق خلف الأخبار بأسلوب احترافي.'],
                ['النشرات الإخبارية','آخر الأخبار والتحديثات مباشرة إلى بريدك الإلكتروني.'],
                ['البث المباشر','تغطيات حصرية وبث مباشر للأحداث السياسية والاقتصادية والرياضية.'],
            ] as [$t, $d]) {
                Service::create(['title' => $t, 'description' => $d]);
            }
        }

        if (! Testimonial::count()) {
            foreach ([['أحمد ك.','الجريدة هي مصدري الأول للأخبار الموثوقة.'],['ليلى س.','أتابع الجريدة يوميًا لمعرفة آخر الأخبار العاجلة.'],['سعيد م.','حيادية ومصداقية في نقل الأخبار.']] as [$n, $c]) {
                Testimonial::create(['author_name' => $n, 'content' => $c, 'rating' => 5]);
            }
        }

        if (! TeamMember::count()) {
            foreach ([['محمد العلي','رئيس التحرير'],['ليلى النجار','محررة الأخبار'],['خالد يوسف','مراسل ميداني'],['سارة عمران','صحفية تحقيقات']] as [$n, $p]) {
                TeamMember::create(['name' => $n, 'position' => $p]);
            }
        }

        if (! Blog::count()) {
            Blog::create(['title' => 'ابق على اطلاع: أهم الأخبار والتحليلات', 'content' => '<p>تابع آخر التطورات في عالم الإعلام.</p>', 'author' => 'محمد علي', 'published_at' => now()]);
        }

        if (! Article::count()) {
            Article::create([
                'title' => 'مرحبا بكم في الجريدة', 'content' => '<p>هذا أول مقال في موقع الجريدة.</p>',
                'user_id' => $admin->id,
                'category_id' => Category::where('slug','news')->value('id'), 'is_published' => true, 'published_at' => now(),
            ]);
        }
    }
}

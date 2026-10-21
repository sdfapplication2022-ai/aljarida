<?php

namespace Database\Seeders;

use App\Models\{
    About, Article, ArticleLike, Blog, Category, Comment,
    Service, Subscriber, Tag, TeamMember, Testimonial, User
};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Création de l'utilisateur Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@aljarida.tn'],
            [
                'name' => 'Administrateur',
                'password' => 'password',
                'role' => 'admin',
            ]
        );

        // 2. Catégories
        $categories = [
            ['id' => 1, 'name' => 'أخبار', 'slug' => 'news'],
            ['id' => 2, 'name' => 'تعليم', 'slug' => 'education'],
            ['id' => 3, 'name' => 'مرأة', 'slug' => 'women'],
            ['id' => 4, 'name' => 'ثقافة', 'slug' => 'culture'],
            ['id' => 5, 'name' => 'سياحة', 'slug' => 'tourisme'],
            ['id' => 6, 'name' => 'رياضة', 'slug' => 'Sport'],
        ];
        foreach ($categories as $cat) {
            Category::updateOrCreate(['id' => $cat['id']], $cat);
        }

        // 3. Tags
        $tags = [
            ['id' => 1, 'name' => 'جامعة_جندوبة', 'slug' => 'جامعة-جندوبة'],
            ['id' => 2, 'name' => 'تصنيف_THE_2027', 'slug' => 'تصنيف-THE-2027'],
            ['id' => 3, 'name' => 'التميز_الأكاديمي', 'slug' => 'التميز-الأكاديمي'],
            ['id' => 4, 'name' => 'البحث_العلمي', 'slug' => 'البحث-العلمي'],
            ['id' => 5, 'name' => 'Tabarka', 'slug' => 'Tabarka'],
        ];
        foreach ($tags as $tag) {
            Tag::updateOrCreate(['id' => $tag['id']], $tag);
        }

        // 4. Équipe (Team Members)
        $team = [
            ['id' => 1, 'name' => 'روضة مديوني', 'position' => 'رئيس التحرير', 'photo' => 'uploads/teams/ArWSf6PJI6Dqiol9u1MuUUjqXoWsJpK9qbcAPLYA.jpg'],
            ['id' => 2, 'name' => 'ضياء مازني', 'position' => 'مطور واب', 'photo' => 'uploads/teams/0FNYcWNOWUOLXeB0M3hfqBKsiWQ85kM5y6RgL5Ec.png'],
            ['id' => 3, 'name' => 'علاء مديوني', 'position' => 'مراسل ميداني', 'photo' => 'uploads/teams/xXgdgnZ3niaWm7gc57os8HK8bFN9SwiQy7kfay1L.jpg'],
            ['id' => 4, 'name' => 'سارة عمران', 'position' => 'صحفية تحقيقات', 'photo' => 'uploads/teams/5bkIFoVJ3W7ME0jv3sGPfQIcghroG29B1UE59fA5.png'],
        ];
        foreach ($team as $member) {
            TeamMember::updateOrCreate(['id' => $member['id']], $member);
        }

        // 5. Services
        $services = [
            ['id' => 1, 'title' => 'التقارير الحصرية', 'description' => '<p>تحليلات معمقة وتقارير ميدانية حصرية تضعك في قلب الأحداث.</p>', 'photo' => 'uploads/services/iL0UYwzZkxcbls2yfB281S2dXIQzpgOPFSv8Yo4s.png'],
            ['id' => 2, 'title' => 'التحقيقات الصحفية', 'description' => '<p>تحقيقات معمقة تكشف الحقائق خلف الأخبار بأسلوب احترافي.</p>', 'photo' => 'uploads/services/VEJ7BXAqQyT9F60NR87blC506IwXxX3iY8dBb1Jo.jpg'],
            ['id' => 3, 'title' => 'النشرات الإخبارية', 'description' => '<p>آخر الأخبار والتحديثات مباشرة إلى بريدك الإلكتروني.</p>', 'photo' => 'uploads/services/4CgHeAcjmJUe4agtzQ1QR0AcrNSr15WjjZ5XWwrb.png'],
            ['id' => 4, 'title' => 'البث المباشر', 'description' => '<p>تغطيات حصرية وبث مباشر للأحداث السياسية والاقتصادية والرياضية.</p>', 'photo' => 'uploads/services/bKIblsSnpGR1ZscIJcDuam7x5m6dqrONx4AqV7sU.png'],
        ];
        foreach ($services as $srv) {
            Service::updateOrCreate(['id' => $srv['id']], $srv);
        }

        // 6. Témoignages (Testimonials)
        $testimonials = [
            ['id' => 1, 'author_name' => 'أحمد ك.', 'content' => 'الجريدة هي مصدري الأول للأخبار الموثوقة.', 'rating' => 5],
            ['id' => 2, 'author_name' => 'ليلى س.', 'content' => 'أتابع الجريدة يوميًا لمعرفة آخر الأخبار العاجلة.', 'rating' => 5],
            ['id' => 3, 'author_name' => 'سعيد م.', 'content' => 'حيادية ومصداقية في نقل الأخبار.', 'rating' => 5],
        ];
        foreach ($testimonials as $test) {
            Testimonial::updateOrCreate(['id' => $test['id']], $test);
        }

        // 7. About
        About::updateOrCreate(['id' => 1], [
            'title' => 'نقدم الأخبار بحيادية ودقة متناهية',
            'subtitle' => 'من نحن',
            'description' => '<p><span>نحن منصة إخبارية مستقلة تضع الحقيقة في صدارة أولوياتها. نواكب الأحداث لحظة بلحظة، ونفكك المشهد من حولنا لنقدم لكم محتوى رصيناً يعكس الواقع بشفافية ومصداقية. نلتزم في "الجريدة" بأخلاقيات الصحافة المهنية، فنحن بوصلتكم الموثوقة وسط ضجيج المعلومات، لننير وعيكم ونضع بين أيديكم الخبر من مصدره دون تحيز أو تزييف.</span></p>',
            'main_image' => 'uploads/about/NOg5Yvk55sbhUG4VpQROr5TFnoI0unsF7jjTiNkO.jpg',
            'secondary_image' => 'uploads/about/YxpOO2fRS1KBwYKv484j0OSXn0zT4FPeA78nZwzN.webp',
        ]);

        // 8. Blogs
        Blog::updateOrCreate(['id' => 1], [
            'title' => 'ابق على اطلاع: أهم الأخبار والتحليلات',
            'author' => 'روضة مديوني',
            'published_at' => '2026-10-17 06:45:00',
            'image' => 'uploads/blogs/lJnHXhs8Ew7q1QewBwjYZsYZsGbY1dPFtvsO2Hze.jpg',
            'content' => '<p><span>📚✨ </span><strong><span>التعلّم طريقك نحو النجاح والتميّز! 🎓</span></strong></p><p><span>هل تعلم أن النجاح في الدراسة لا يعتمد فقط على عدد الساعات التي تقضيها في المراجعة، بل يعتمد أيضًا على الطريقة التي تتعلّم بها وتنظّم بها وقتك؟ 🧠💡</span></p><p><span>إليك 5 نصائح تساعدك على تحسين مستواك الدراسي وتحقيق نتائج أفضل:</span></p><p><span>📌 </span><strong><span>1. نظّم وقتك بذكاء:</span></strong><span> ضع برنامجًا يوميًا للمراجعة، وحدّد وقتًا لكل مادة، ولا تؤجّل عمل اليوم إلى الغد.</span></p><p><span>📖 </span><strong><span>2. افهم قبل أن تحفظ:</span></strong><span> حاول فهم الدرس واستخراج أفكاره الأساسية، ثم استخدم التلخيص والأمثلة لتثبيت المعلومات في ذهنك.</span></p><p><span>⏰ </span><strong><span>3. اعتمد فترات مراجعة قصيرة:</span></strong><span> ذاكر لمدة 25 دقيقة بتركيز، ثم خذ استراحة لمدة 5 دقائق. كرّر هذه الطريقة حسب حاجتك.</span></p><p><span>✍️ </span><strong><span>4. اختبر نفسك باستمرار:</span></strong><span> بعد كل درس، حاول الإجابة عن الأسئلة دون الرجوع إلى الكتاب. بهذه الطريقة ستكتشف نقاط قوتك والجوانب التي تحتاج إلى تحسينها.</span></p><p><span>😴 </span><strong><span>5. اهتم بصحتك:</span></strong><span> احرص على النوم الكافي، وشرب الماء، وممارسة بعض النشاط البدني، لأن العقل يحتاج إلى الراحة ليستوعب المعلومات جيدًا.</span></p><p><span>🌟 </span><strong><span>تذكّر:</span></strong><span> لا تقارن نفسك بالآخرين، بل قارن مستواك اليوم بمستواك بالأمس. التقدّم البسيط والمستمر أفضل من الاجتهاد المتقطّع.</span></p><p><span>💬 </span><strong><span>سؤال للنقاش:</span></strong><br><span>ما هي الطريقة التي تساعدك أكثر على الحفظ والفهم: التلخيص، حلّ التمارين، مشاهدة الفيديوهات التعليمية، أم الدراسة مع الأصدقاء؟ 🤔</span></p><p><span>شاركنا تجربتك في التعليقات، فقد تكون نصيحتك سببًا في نجاح طالب آخر! ❤️</span></p><p><span>#التعليم #النجاح_الدراسي #نصائح_للدراسة #التعلّم #التحفيز #تطوير_الذات</span></p>'
        ]);

        Blog::updateOrCreate(['id' => 2], [
            'title' => '🎓📚 كيف تتعلّم بذكاء وليس لساعات طويلة؟ 🧠✨',
            'author' => 'روضة مديوني',
            'published_at' => '2026-10-12 08:10:00',
            'image' => 'uploads/blogs/kQywap9XIbLbTETF1kZM70uzhB4JHRQ5AEG0DVSj.png',
            'content' => '<p><span>🎓📚 </span><strong><span>كيف تتعلّم بذكاء وليس لساعات طويلة؟ 🧠✨</span></strong></p><p><span>يعتقد الكثير من الطلاب أن النجاح يتطلّب الدراسة لساعات طويلة، لكن الحقيقة أن </span><strong><span>جودة التعلّم أهم من عدد ساعات الدراسة!</span></strong><span> 💡</span></p><p><span>إليك بعض الطرق الفعّالة التي تساعدك على التعلّم بشكل أفضل وتحقيق أهدافك الدراسية:</span></p><p><span>🧠 </span><strong><span>1. استخدم تقنية الاسترجاع النشط</span></strong><br><span>بعد قراءة الدرس، أغلق الكتاب وحاول شرح ما تعلّمته بأسلوبك الخاص. هذه الطريقة تساعدك على تذكّر المعلومات وفهمها بعمق.</span></p><p><span>🔄 </span><strong><span>2. راجع المعلومات على فترات متباعدة</span></strong><br><span>لا تنتظر حتى موعد الامتحان لمراجعة دروسك. راجع المعلومات بعد يوم، ثم بعد عدة أيام، ثم بعد أسبوع، حتى تتمكّن من تثبيتها في ذاكرتك.</span></p><p><span>📵 </span><strong><span>3. ابتعد عن مصادر التشتيت</span></strong><br><span>أغلق الإشعارات وضع هاتفك جانبًا أثناء الدراسة. خصّص وقتك لمهمة واحدة، وستلاحظ تحسّنًا في تركيزك.</span></p><p><span>📝 </span><strong><span>4. تعلّم من أخطائك</span></strong><br><span>عندما تخطئ في تمرين أو إجابة، لا تكتفِ بمعرفة الحل الصحيح، بل حاول فهم سبب الخطأ حتى لا تكرّره مستقبلًا.</span></p><p><span>👥 </span><strong><span>5. اشرح ما تعلّمته لشخص آخر</span></strong><br><span>عندما تستطيع شرح فكرة معقّدة بطريقة بسيطة، فهذا دليل على أنك بدأت تفهمها جيدًا.</span></p><p><span>🌱 </span><strong><span>6. اجعل التعلّم عادة يومية</span></strong><br><span>خصّص حتى 20 دقيقة يوميًا لاكتساب معرفة جديدة. اقرأ كتابًا، تعلّم لغة، شاهد درسًا مفيدًا أو اكتشف مهارة جديدة.</span></p><p><span>✨ </span><strong><span>رسالة اليوم:</span></strong><br><span>لا تحتاج إلى أن تكون الأذكى في القاعة، بل تحتاج إلى الاستمرار، والصبر، والرغبة في التطوّر. كل معلومة تتعلّمها اليوم قد تفتح لك بابًا جديدًا في المستقبل! 🚀</span></p><p><span>💬 </span><strong><span>سؤال للنقاش:</span></strong><br><span>إذا كان بإمكانك تعلّم مهارة جديدة هذا الشهر، فماذا ستختار: البرمجة 💻، اللغة الإنجليزية 🌍، التصميم 🎨، أم مهارات التواصل 🗣️؟</span></p><p><span>👇 شاركنا اختيارك في التعليقات، ولنواصل التعلّم معًا!</span></p><p><span>#التعليم #التعلّم_الذكي #تطوير_الذات #النجاح #مهارات_جديدة #الثقافة</span></p>'
        ]);

        // 9. Articles
        $a1 = Article::updateOrCreate(['id' => 1], [
            'category_id' => 1, 'user_id' => $admin->id, 'title' => 'مرحبا بكم في الجريدة',
            'slug' => 'mrhba-bkm-fy-algryd', 'content' => '<p>هذا أول مقال في موقع الجريدة.</p>',
            'image' => 'uploads/articles/5M2EdwwfcZYF5T1BKtZlqrFWDu0Eok1ZwJe2lT46.webp',
            'is_published' => 1, 'is_featured' => 0, 'published_at' => '2026-10-17 06:45:15', 'views' => 7,
        ]);

        $a2 = Article::updateOrCreate(['id' => 2], [
            'category_id' => 2, 'user_id' => $admin->id, 'title' => 'PEEJ-Pôle Etudiant Entrepreneur de l\'Université de Jendouba',
            'slug' => 'peej-pole-etudiant-entrepreneur-de-luniversite-de-jendouba',
            'content' => '<p><span><img src="https://static.xx.fbcdn.net/images/emoji.php/v9/tc6/1/16/1f680.png" alt="1f680.png"></span><span> APPEL À CANDIDATURES | STATUT NATIONAL D’ÉTUDIANT-ENTREPRENEUR (SNEE)</span><br><span><img src="https://static.xx.fbcdn.net/images/emoji.php/v9/t25/1/16/1f393.png" alt="1f393.png"></span><span> 4ᵉ Cohorte | Année universitaire 2026-2027</span><br><br><span><img src="https://static.xx.fbcdn.net/images/emoji.php/v9/t3c/1/16/1f4a1.png" alt="1f4a1.png"></span><span> Votre idée mérite de devenir un projet. Votre projet mérite de devenir une startup !</span><br><br><span>Vous êtes étudiant(e) et vous avez une idée innovante, une ambition entrepreneuriale ou un projet que vous souhaitez concrétiser ? Vous souhaitez entreprendre tout en poursuivant vos études universitaires ?</span><br></p>',
            'image' => 'uploads/articles/rg1zb5JtwIRUjgVkYC1iVf7fCsxpc5ItfpFmmpAM.jpg',
            'is_published' => 1, 'is_featured' => 0, 'published_at' => '2026-10-17 07:30:00', 'views' => 24,
        ]);

        $a3 = Article::updateOrCreate(['id' => 3], [
            'category_id' => 2, 'user_id' => $admin->id, 'title' => '🎓 تصنيف THE 2027: جامعة جندوبة تواصل مسارها التصاعدي نحو العالمية 🌍و تحتل المرتبة الثانية وطنيا',
            'slug' => 'tsnyf-the-2027-gamaa-gndob-toasl-msarha-altsaaady-nho-alaaalmy-o-thtl-almrtb-althany-otnya',
            'content' => '<p><span><img src="https://static.xx.fbcdn.net/images/emoji.php/v9/t25/1/16/1f393.png" alt="1f393.png"></span><span> تصنيف THE 2027: جامعة جندوبة تواصل مسارها التصاعدي نحو العالمية </span><span><img src="https://static.xx.fbcdn.net/images/emoji.php/v9/t3f/1/16/1f30d.png" alt="1f30d.png"></span><span>و تحتل المرتبة الثانية وطنيا</span><br><br><span>يسرّ جامعة جندوبة أن تعلن عن حضورها في التصنيف العالمي للجامعات Times Higher Education (THE) 2027، الذي تم نشره يوم 30 سبتمبر 2026، والذي يشمل هذه السنة 2297 مؤسسة جامعية موزعة على 118 دولة.</span><br><br><span>وقد ثمّنت وزارة التعليم العالي والبحث العلمي التقدم السريع الذي حققته جامعة جندوبة، ضمن إحدى عشرة جامعة تونسية تم إدراجها في التصنيف هذه السنة، في رقم قياسي على المستوى الوطني.</span><br><br><span>وتُعدّ هذه المكانة الدولية ثمرةً للعمل الجماعي المتواصل لكافة أفراد أسرة جامعة جندوبة، من أساتذة وباحثين، ومخابر ووحدات بحث، وطلبة دكتوراه وطلبة، وإطارات وأعوان إداريين وفنيين، وكافة مؤسسات الجامعة.</span><br><br><span>كما تعكس هذه النتيجة جهود الجامعة المتواصلة في مجالات جودة التعليم، والإنتاج العلمي والبحث، والانفتاح الدولي، وتعزيز الشراكات مع المحيط الاقتصادي والاجتماعي.</span><br><br><span>وتمثل هذه النتيجة في الآن ذاته مصدر فخر ومسؤولية والتزامًا متجددًا بمواصلة تنفيذ استراتيجية الجامعة، والارتقاء بجودة التعليم والبحث العلمي، وتعزيز إشعاع الجامعة، وخدمة جهتنا ووطننا على أفضل وجه.</span><br><br><span><img src="https://static.xx.fbcdn.net/images/emoji.php/v9/t80/1/16/1f64f.png" alt="1f64f.png"></span><span> كل الشكر والتقدير لكل من ساهم في تحقيق هذا الإنجاز، ولكافة أفراد أسرة جامعة جندوبة على التزامهم وعطائهم المتواصل.</span><br><br><span><img src="https://static.xx.fbcdn.net/images/emoji.php/v9/t3f/1/16/1f30d.png" alt="1f30d.png"></span><span> معًا، نواصل مسيرة جامعة جندوبة نحو العالمية بجذور محلية.</span><br><br></p>',
            'image' => 'uploads/articles/c7BWma3GyLRqpW0HA47KchIMHX14dNnlEUML4ORY.jpg',
            'is_published' => 1, 'is_featured' => 0, 'published_at' => '2026-10-16 08:53:00', 'views' => 9,
        ]);
        $a3->tags()->sync([1, 3, 4]); // Liaison des tags

        $a4 = Article::updateOrCreate(['id' => 4], [
            'category_id' => 5, 'user_id' => $admin->id, 'title' => '🌊💚 طبرقة... سحر الطبيعة وجمال البحر في تونس! 🇹🇳',
            'slug' => 'tbrk-shr-altbyaa-ogmal-albhr-fy-tons',
            'content' => '<p><span>🌊💚 </span><strong><span>طبرقة... سحر الطبيعة وجمال البحر في تونس! 🇹🇳</span></strong></p><p><span>هل تبحث عن وجهة سياحية تجمع بين زرقة البحر، وخضرة الجبال، وسحر الطبيعة؟ 😍🏞️</span></p><p><span>مرحبًا بك في </span><strong><span>طبرقة، لؤلؤة الشمال الغربي التونسي</span></strong><span>، المدينة التي تأسر القلوب بجمالها الطبيعي وأجوائها الهادئة، وتمنح زوّارها تجربة سياحية لا تُنسى! ✨</span></p><p><span>📍 </span><strong><span>لماذا تستحق طبرقة الزيارة؟</span></strong></p><p><span>🌊 </span><strong><span>1. الشواطئ الساحرة:</span></strong><span> استمتع بمياه البحر المتوسط الصافية، والرمال الجميلة، وأجواء الاسترخاء تحت أشعة الشمس.</span></p><p><span>🪨 </span><strong><span>2. الإبر الصخرية:</span></strong><span> اكتشف التكوينات الصخرية الشهيرة التي ترتفع بمحاذاة البحر، وشاهد منظرًا طبيعيًا رائعًا يستحق التصوير! 📸</span></p><p><span>🌿 </span><strong><span>3. الغابات والجبال:</span></strong><span> تتميّز طبرقة بمحيط طبيعي أخضر يجمع بين الغابات الكثيفة والتضاريس الجبلية، مما يجعلها وجهة رائعة لمحبي المغامرة والطبيعة.</span></p><p><span>🤿 </span><strong><span>4. الغوص واكتشاف الأعماق:</span></strong><span> تُعرف المنطقة بجمال عالمها البحري، وهي وجهة مميزة لعشاق الغوص واستكشاف الحياة تحت الماء، حسب الظروف والأنشطة المتاحة.</span></p><p><span>🏰 </span><strong><span>5. التاريخ والثقافة:</span></strong><span> يمكنك اكتشاف قلعة طبرقة والاستمتاع بإطلالاتها الجميلة، والتعرّف على جانب من تاريخ المدينة العريق.</span></p><p><span>🐟 </span><strong><span>6. المطبخ المحلي:</span></strong><span> لا تفوّت فرصة تذوّق الأطباق البحرية والمأكولات التونسية التقليدية التي تضيف نكهة خاصة إلى رحلتك. 😋</span></p><p><span>💚 </span><strong><span>طبرقة ليست مجرد وجهة سياحية، بل تجربة تجمع بين الراحة والمغامرة واكتشاف جمال تونس الطبيعي.</span></strong></p><p><span>📸 تخيّل نفسك أمام البحر، تستمتع بنسيمه العليل، وتراقب غروب الشمس وسط لوحة طبيعية خلّابة! 🌅</span></p><p><span>💬 </span><strong><span>سؤال لمحبي السفر:</span></strong><br><span>إذا زرت طبرقة، ماذا ستختار أولًا؟</span></p><p><span>🌊 الاستمتاع بالشاطئ</span><br><span>🌲 اكتشاف الغابات والجبال</span><br><span>🤿 تجربة الغوص</span><br><span>📸 التقاط الصور عند الإبر الصخرية</span></p><p><span>👇 شاركنا اختيارك في التعليقات، واذكر الشخص الذي تتمنى اكتشاف طبرقة معه! ❤️</span></p><p><span>#طبرقة #السياحة_في_تونس #اكتشف_تونس #سياحة #جمال_الطبيعة #Tabarka #TunisiaTravel 🇹🇳</span></p>',
            'image' => null,
            'video_url' => 'uploads/videos/cn59lxXaibznyv8qqxOQLvJq7U82iOSQhCTJMfoZ.mp4',
            'is_published' => 1, 'is_featured' => 0, 'published_at' => '2026-10-14 08:19:00', 'views' => 6,
        ]);
        $a4->tags()->sync([5]);

        $a5 = Article::updateOrCreate(['id' => 5], [
            'category_id' => 6, 'user_id' => $admin->id, 'title' => '⚽🔥 الرياضة أسلوب حياة... والإرادة طريق النجاح! 🏆💪',
            'slug' => 'alryad-aslob-hya-oalarad-tryk-alngah',
            'excerpt' => '⚽🔥 الرياضة أسلوب حياة... والإرادة طريق النجاح! 🏆💪 الرياضة ليست مجرد منافسة أو البحث عن الفوز، بل هي مدرسة تعلّمنا الانضباط، والصبر، والع...',
            'content' => '<p>⚽🔥 <strong>الرياضة أسلوب حياة... والإرادة طريق النجاح! 🏆💪</strong></p><p>الرياضة ليست مجرد منافسة أو البحث عن الفوز، بل هي مدرسة تعلّمنا الانضباط، والصبر، والعمل الجماعي، واحترام الآخرين. ❤️</p><p>🏃‍♂️ <strong>لماذا يجب أن نجعل الرياضة جزءًا من حياتنا؟</strong></p><p>💪 <strong>1. جسم صحي:</strong> تساعد ممارسة النشاط البدني بانتظام على تحسين اللياقة البدنية وتقوية العضلات.</p><p>🧠 <strong>2. عقل أكثر نشاطًا:</strong> تساهم الرياضة في تحسين المزاج وتقليل التوتر ودعم التركيز.</p><p>🤝 <strong>3. روح الفريق:</strong> تعلّمنا الرياضات الجماعية التعاون والثقة المتبادلة وتحمل المسؤولية.</p><p>🎯 <strong>4. الانضباط والإصرار:</strong> كل تدريب هو فرصة لتطوير قدراتنا وتجاوز حدودنا وتحقيق أهداف جديدة.</p><p>🏅 <strong>5. الروح الرياضية:</strong> البطل الحقيقي ليس من يفوز فقط، بل من يحترم منافسيه ويتقبّل الخسارة ويتعلّم من أخطائه.</p><p>🌟 <strong>تذكّر:</strong> لا تحتاج إلى أن تكون بطلًا عالميًا حتى تستفيد من الرياضة. ابدأ بخطوات بسيطة، واستمر، واجعل الحركة عادة يومية تناسب قدراتك.</p><p>🔥 النجاح لا يأتي في يوم واحد، بل يُبنى بالتدريب والإرادة والاستمرارية!</p><p>💬 <strong>سؤال لعشّاق الرياضة:</strong><br>ما هي رياضتك المفضّلة؟ ⚽ كرة القدم، 🏀 كرة السلة، 🏐 الكرة الطائرة، 🏊 السباحة، أم رياضة أخرى؟</p><p>👇 شاركنا رأيك في التعليقات، وأخبرنا بفريقك المفضّل! ❤️</p><p>#الرياضة #كرة_القدم #الصحة #اللياقة_البدنية #الروح_الرياضية #النجاح #Sport</p>',
            'image' => 'uploads/articles/j463jdRl2Mom9WfFC4AlKK6RorY9m1ar86WaVEhB.jpg',
            'is_published' => 1, 'is_featured' => 1, 'published_at' => '2026-10-15 14:23:00', 'views' => 4,
        ]);

        // 10. Commentaires
        Comment::updateOrCreate(['id' => 2], [
            'article_id' => 2,
            'guest_name' => 'عبير منصوري',
            'guest_email' => 'abir@live.fr',
            'visitor_token' => 'f0577855-78c2-47cc-acff-b114a174e2f6',
            'content' => 'QUI PEUT CANDIDATER ?',
            'is_approved' => true,
        ]);

        // 11. Likes (Article 2 par admin et visiteur, Article 4 par admin)
        ArticleLike::updateOrCreate(['id' => 3], ['article_id' => 2, 'user_id' => $admin->id]);
        ArticleLike::updateOrCreate(['id' => 4], ['article_id' => 2, 'visitor_token' => 'f0577855-78c2-47cc-acff-b114a174e2f6']);
        ArticleLike::updateOrCreate(['id' => 5], ['article_id' => 4, 'user_id' => $admin->id]);

        // 12. Abonnés (Subscribers)
        Subscriber::updateOrCreate(['email' => 'abir@live.fr'], [
            'is_active' => true,
        ]);
    }
}

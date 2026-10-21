<!--===== CTA AREA STARTS =======-->
<div class="cta12-section-area sp4" style="background-image: url({{ asset('assets/img/bg/cta-bg6.png') }});">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5">
                <div class="cta-header">
                    <h2 class="text-white">اشترك في نشرتنا البريدية لتصلك أحدث الأخبار أولاً بأول</h2>
                    <div class="space32"></div>
                    <form action="{{ route('subscribe.store') }}" method="POST">
                        @csrf
                        <div style="position:absolute;left:-9999px" aria-hidden="true">
                            <input type="text" name="website" tabindex="-1" autocomplete="off">
                        </div>
                        <input type="email" name="email" placeholder="بريدك الإلكتروني" required>
                        <button type="submit" class="header-btn20">اشترك <i class="fa-solid fa-angle-left"></i></button>
                    </form>
                </div>
            </div>
            <div class="col-lg-1"></div>
            <div class="col-lg-3 col-md-6">
                <div class="cta-box">
                    <div class="icon"><img src="https://seoc-html-v2.vercel.app/assets/img/icons/phone5.svg" alt=""></div>
                    <div class="text"><p>اتصل بنا 24/7</p><div class="space14"></div><a dir="ltr" href="tel:+21690033504"> +216 90 033 504</a></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="space30 d-md-none d-block"></div>
                <div class="cta-box">
                    <div class="icon"><img src="https://seoc-html-v2.vercel.app/assets/img/icons/email5.svg" alt=""></div>
                    <div class="text"><p>راسلنا في أي وقت</p><div class="space14"></div><a href="mailto:info@aljareeda.tn">info@aljareeda.tn</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--===== CTA AREA ENDS =======-->
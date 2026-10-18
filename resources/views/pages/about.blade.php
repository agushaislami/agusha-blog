@extends('app')
@section('title', ($language === 'id' ? 'Tentang Saya' : 'About Me') . ' | Agusha Blog')

@section('content')
<!-- Page Header-->
<header class="masthead" style="background-image: url('assets/img/about-bg.jpg')">
    <div class="container position-relative px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <div class="page-heading">
                    <h1 id="aboutTitle" data-en="About Me" data-id="Tentang Saya">{{ $language === 'id' ? 'Tentang Saya' : 'About Me' }}</h1>
                    <span class="subheading" id="aboutSubtitle" data-en="Building, learning, and sharing through code." data-id="Berkarya, belajar, dan berbagi melalui kode.">{{ $language === 'id' ? 'Berkarya, belajar, dan berbagi melalui kode.' : 'Building, learning, and sharing through code.' }}</span>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Main Content-->
<main class="mb-4">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <nav class="about-language-switch" aria-label="Pilih bahasa">
                    <button class="{{ $language === 'en' ? 'active' : '' }}" type="button" data-about-language="en" lang="en" aria-pressed="{{ $language === 'en' ? 'true' : 'false' }}">English</button>
                    <button class="{{ $language === 'id' ? 'active' : '' }}" type="button" data-about-language="id" lang="id" aria-pressed="{{ $language === 'id' ? 'true' : 'false' }}">Indonesia</button>
                </nav>
                <div class="about-copy" data-about-copy="id" lang="id" @if ($language !== 'id') hidden @endif>
                    <p>Halo! Saya Agusha, penggemar pengembangan perangkat lunak dari Batam, Indonesia, yang memiliki minat besar pada teknologi, pemrograman, dan proses belajar tanpa henti.</p>
                    <p>Perjalanan saya dalam pengembangan perangkat lunak diisi dengan menjelajahi teknologi baru, membangun proyek praktis, dan memahami cara kerja berbagai hal di balik layar. Mulai dari mengembangkan aplikasi web dengan Laravel hingga bereksperimen dengan Linux dan mencoba berbagai alat, saya senang mengubah ide menjadi solusi nyata dan belajar dari setiap tantangan.</p>
                    <p>Saya percaya bahwa pengetahuan tumbuh ketika dibagikan. Karena itulah Agusha Blog hadir: sebagai tempat untuk menjelajahi ide, mendokumentasikan pengalaman, bertukar sudut pandang, dan belajar bersama. Baik itu temuan teknis, refleksi pribadi, maupun pelajaran dari proses mencoba dan gagal, setiap cerita punya sesuatu yang berharga untuk dibagikan.</p>
                    <p>Saya masih terus belajar, berkarya, dan selalu ingin tahu tentang hal berikutnya. Melalui blog ini, saya berharap dapat berbagi perjalanan, terhubung dengan sesama pembelajar dan kreator, serta menginspirasi orang lain untuk terus menjelajah, bertanya, dan berkarya.</p>
                </div>
                <div class="about-copy" data-about-copy="en" lang="en" @if ($language !== 'en') hidden @endif>
                    <p>Hello! I'm Agusha, a software development enthusiast from Batam, Indonesia, with a passion for technology, programming, and continuous learning.</p>
                    <p>My journey in software development revolves around exploring new technologies, building practical projects, and understanding how things work behind the scenes. From developing web applications with Laravel to experimenting with Linux and exploring different tools, I enjoy turning ideas into working solutions and learning from every challenge along the way.</p>
                    <p>I believe that knowledge grows when it is shared. That's why Agusha Blog exists: a place to explore ideas, document experiences, exchange perspectives, and learn from one another. Whether it's a technical discovery, a personal reflection, or a lesson learned through trial and error, every story has something valuable to offer.</p>
                    <p>I'm still learning, still building, and always curious about what comes next. Through this blog, I hope to share my journey, connect with fellow learners and creators, and inspire others to keep exploring, questioning, and creating.</p>
                </div>
                - Agusha
            </div>
        </div>
    </div>
</main>
<style>
    .about-language-switch { display: flex; width: fit-content; gap: .25rem; margin: 0 0 1.5rem; padding: .25rem; border: 1px solid #dfe7e9; border-radius: 999px; background: #f5f8f9; }
    .about-language-switch button { padding: .35rem .8rem; border: 0; border-radius: 999px; background: transparent; color: #62747a; font-size: .78rem; font-weight: 700; }
    .about-language-switch button:hover { color: #08758a; }
    .about-language-switch button.active { background: #08758a; color: #fff; }
    .about-language-switch button:focus-visible { outline: 2px solid #08758a; outline-offset: 2px; }
    .about-copy { opacity: 1; transition: opacity .45s ease; }
    .about-copy.is-fading { opacity: 0; }
    @media (prefers-reduced-motion: reduce) { .about-copy { transition: none; } }
    html[data-theme="dark"] .about-language-switch { border-color: #35474c; background: #172328; }
    html[data-theme="dark"] .about-language-switch button { color: #b5c5c8; }
    html[data-theme="dark"] .about-language-switch button:hover { color: #8de0ed; }
    html[data-theme="dark"] .about-language-switch button.active { background: #087c91; color: #fff; }
</style>
<script>
    (() => {
        const title = document.getElementById('aboutTitle');
        const subtitle = document.getElementById('aboutSubtitle');
        const languageButtons = document.querySelectorAll('[data-about-language]');
        const languageCopies = document.querySelectorAll('[data-about-copy]');
        let languageTransitionTimeout;
        let languageTransitionFrame;
        let currentLanguage = document.querySelector('[data-about-copy]:not([hidden])')?.dataset.aboutCopy ?? 'en';

        const setLanguage = (language, updateHistory = false) => {
            const selectedLanguage = language === 'id' ? 'id' : 'en';
            const currentCopy = document.querySelector('[data-about-copy]:not([hidden])');

            if (selectedLanguage === currentLanguage) {
                window.clearTimeout(languageTransitionTimeout);
                window.cancelAnimationFrame(languageTransitionFrame);
                currentCopy?.classList.remove('is-fading');

                return;
            }

            window.clearTimeout(languageTransitionTimeout);
            window.cancelAnimationFrame(languageTransitionFrame);
            currentCopy?.classList.add('is-fading');

            languageTransitionTimeout = window.setTimeout(() => {
                title.textContent = title.dataset[selectedLanguage];
                subtitle.textContent = subtitle.dataset[selectedLanguage];
                currentLanguage = selectedLanguage;
                document.title = `${title.textContent} | Agusha Blog`;
                document.documentElement.lang = selectedLanguage;

                languageButtons.forEach((button) => {
                    const isActive = button.dataset.aboutLanguage === selectedLanguage;
                    button.classList.toggle('active', isActive);
                    button.setAttribute('aria-pressed', String(isActive));
                });

                languageCopies.forEach((copy) => {
                    copy.hidden = copy.dataset.aboutCopy !== selectedLanguage;
                });

                if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    const enteringCopy = document.querySelector('[data-about-copy]:not([hidden])');
                    enteringCopy?.classList.add('is-fading');

                    languageTransitionFrame = window.requestAnimationFrame(() => {
                        languageTransitionFrame = window.requestAnimationFrame(() => {
                            enteringCopy?.classList.remove('is-fading');
                        });
                    });
                }

                if (updateHistory) {
                    const url = new URL(window.location.href);
                    url.searchParams.set('lang', selectedLanguage);
                    window.history.pushState({ language: selectedLanguage }, '', url);
                }
            }, window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 0 : 450);
        };

        languageButtons.forEach((button) => {
            button.addEventListener('click', () => setLanguage(button.dataset.aboutLanguage, true));
        });

        window.addEventListener('popstate', () => {
            const language = new URL(window.location.href).searchParams.get('lang');
            setLanguage(language === 'id' ? 'id' : 'en');
        });
    })();
</script>
@endsection

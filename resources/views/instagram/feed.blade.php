<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Instagram Feed</title>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-900 text-slate-100 min-h-screen">

    <div class="max-w-6xl mx-auto px-4 py-10">
        <h1 class="text-3xl md:text-4xl font-bold mb-8">
            Latest <span class="text-indigo-400">Instagram</span> Posts
        </h1>

        {{-- Grid cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($posts as $post)
            <div class="bg-white text-slate-900 rounded-2xl shadow-lg overflow-hidden flex flex-col">

                {{-- MEDIA --}}
                @if($post['media_type'] == 'CAROUSEL_ALBUM' && !empty($post['children']))
                {{-- Carousel / slider sederhana --}}
                <div class="relative aspect-[4/5] bg-slate-200" data-carousel="{{ $post['id'] }}">
                    @foreach($post['children'] as $index => $child)
                    <div class="absolute inset-0 {{ $index === 0 ? '' : 'hidden' }} carousel-slide">
                        @if($child['media_type'] == 'IMAGE')
                        <img src="{{ $child['media_url'] }}"
                            alt="Carousel image"
                            class="w-full h-full object-cover">
                        @elseif($child['media_type'] == 'VIDEO')
                        <video class="w-full h-full object-cover" controls>
                            <source src="{{ $child['media_url'] }}" type="video/mp4">
                        </video>
                        @endif
                    </div>
                    @endforeach

                    {{-- Nav buttons --}}
                    <button type="button"
                        class="absolute left-2 top-1/2 -translate-y-1/2 rounded-full bg-black/60 text-white w-8 h-8 flex items-center justify-center hover:bg-black/80 transition"
                        data-carousel-prev>
                        ‹
                    </button>
                    <button type="button"
                        class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full bg-black/60 text-white w-8 h-8 flex items-center justify-center hover:bg-black/80 transition"
                        data-carousel-next>
                        ›
                    </button>
                </div>

                @elseif($post['media_type'] == 'IMAGE')
                <div class="aspect-[4/5] bg-slate-200">
                    <img src="{{ $post['media_url'] }}"
                        alt="{{ $post['caption'] ?? 'Instagram Post' }}"
                        class="w-full h-full object-cover">
                </div>

                @elseif($post['media_type'] == 'VIDEO')
                <div class="aspect-[4/5] bg-black">
                    <video controls class="w-full h-full object-cover">
                        <source src="{{ $post['media_url'] }}" type="video/mp4">
                    </video>
                </div>
                @endif

                {{-- CONTENT --}}
                <div class="p-4 flex-1 flex flex-col">
                    <p class="font-semibold text-sm text-slate-700">Description</p>

                    {{-- Caption seperti di Instagram (pakai line break) --}}
                    <div class="text-sm text-slate-800 mb-3 leading-relaxed whitespace-pre-line">
                        {{ $post['caption'] ?? '' }}
                    </div>

                    <a href="{{ $post['permalink'] }}" target="_blank"
                        class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-700 font-medium mb-2">
                        View on Instagram
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h4m0 0v4m0-4L10 14" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 5v14h14" />
                        </svg>
                    </a>

                    <span class="mt-auto text-xs text-slate-500">
                        {{ \Carbon\Carbon::parse($post['timestamp'])->diffForHumans() }}
                    </span>
                </div>

            </div>
            @endforeach
        </div>
    </div>

    {{-- JS kecil untuk carousel --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const carousels = document.querySelectorAll('[data-carousel]');

            carousels.forEach(carousel => {
                const slides = carousel.querySelectorAll('.carousel-slide');
                let current = 0;

                const showSlide = (index) => {
                    slides.forEach((slide, i) => {
                        slide.classList.toggle('hidden', i !== index);
                    });
                };

                carousel.querySelector('[data-carousel-prev]')?.addEventListener('click', () => {
                    current = (current - 1 + slides.length) % slides.length;
                    showSlide(current);
                });

                carousel.querySelector('[data-carousel-next]')?.addEventListener('click', () => {
                    current = (current + 1) % slides.length;
                    showSlide(current);
                });
            });
        });
    </script>
</body>

</html>
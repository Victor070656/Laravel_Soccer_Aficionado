@props(['placement' => 'sidebar', 'limit' => 1, 'class' => ''])

@php
    $ads = \App\Models\Ad::running()
        ->forPlacement($placement)
        ->inRandomOrder()
        ->limit($limit)
        ->get();
@endphp

@foreach($ads as $ad)
@php
    // Increment view count (fire-and-forget)
    $ad->increment('view_count');
@endphp
<div class="ad-unit ad-{{ $placement }} {{ $class }}">
    @if($ad->link_url)
    <a href="{{ $ad->link_url }}" target="_blank" rel="noopener sponsored" onclick="fetch('{{ route('ad.click', $ad) }}', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}})">
        <div class="rounded-2xl overflow-hidden border border-zinc-200/60 dark:border-zinc-700/60 shadow-sm hover:shadow-md transition-shadow relative group">
            <img loading="lazy" decoding="async" src="{{ $ad->image_url }}" alt="{{ $ad->title }}" class="w-full object-cover group-hover:scale-[1.02] transition-transform duration-300">
            <div class="absolute bottom-0 right-0 px-2 py-0.5 bg-black/40 backdrop-blur-sm rounded-tl-lg">
                <span class="text-[9px] text-white/70 font-medium uppercase tracking-wider">Ad</span>
            </div>
        </div>
    </a>
    @else
    <div class="rounded-2xl overflow-hidden border border-zinc-200/60 dark:border-zinc-700/60 shadow-sm relative">
        <img loading="lazy" decoding="async" src="{{ $ad->image_url }}" alt="{{ $ad->title }}" class="w-full object-cover">
        <div class="absolute bottom-0 right-0 px-2 py-0.5 bg-black/40 backdrop-blur-sm rounded-tl-lg">
            <span class="text-[9px] text-white/70 font-medium uppercase tracking-wider">Ad</span>
        </div>
    </div>
    @endif
</div>
@endforeach

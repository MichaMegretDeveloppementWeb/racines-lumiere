<section aria-labelledby="concept-title" class="relative overflow-hidden px-6 pt-28 pb-6 text-center lg:pt-44 lg:pb-10">
    <img src="{{ asset('images/brand/emblem-sand.svg') }}" alt="" width="560" height="560" loading="lazy" decoding="async" class="pointer-events-none absolute top-16 left-1/2 w-[20.625rem] -translate-x-1/2 opacity-[0.14] lg:top-[4.375rem] lg:w-[35rem]">

    <x-web.typography.eyebrow class="relative flex-col text-center">Racines &amp; Lumière, maison de soin holistique</x-web.typography.eyebrow>
    <h2 id="concept-title" class="relative mx-auto mt-7 max-w-[21ch] text-[1.5625rem] leading-[1.24] lg:text-[2.75rem]">Ici, pas de protocole répété, <span class="text-terracotta">pas de cabine à la chaîne.</span></h2>
    <p class="relative mx-auto mt-7 max-w-[44ch] leading-[1.8] text-olive lg:text-lg">Chaque rituel naît d'un temps d'échange, puis se compose pour la personne que vous êtes aujourd'hui&nbsp;:</p>

    <div class="relative mx-auto mt-10 h-[33.5rem] max-w-[77.5rem] lg:mt-16 lg:h-[27rem]">
        <ul class="relative font-display text-[0.8125rem] leading-[2.6] tracking-[0.26em] text-terracotta lg:absolute lg:top-6 lg:left-1/2 lg:-translate-x-1/2 lg:text-[0.90625rem]">
            @foreach (['votre corps,', 'votre système nerveux,', 'votre visage,', 'vos émotions,', 'votre énergie…'] as $quality)
                <li class="before:mx-auto before:block before:h-px before:w-6 before:bg-gold first:before:hidden">{{ $quality }}</li>
            @endforeach
        </ul>

        <div aria-hidden="true">
            <div class="absolute overflow-hidden top-[15.5rem] left-[6%] h-[14.5rem] w-[52%] -rotate-3 rounded bg-linear-to-br from-sand to-honey shadow-[0_34px_60px_-34px_rgb(60_40_20/0.55)] lg:top-0 lg:left-[4%] lg:h-[16.875rem] lg:w-[13.125rem] lg:-rotate-4">
                <x-web.media.picture name="home/concept-1" :widths="[240, 480]" sizes="(min-width: 64rem) 210px, 52vw" alt="" :width="240" :height="300" class="size-full object-cover" />
            </div>
            <div class="absolute overflow-hidden top-[27.5rem] left-[36%] z-10 size-24 rotate-3 rounded bg-linear-to-br from-honey to-paper shadow-[0_34px_60px_-34px_rgb(60_40_20/0.55)] lg:top-[12.5rem] lg:left-[21%] lg:size-40">
                <x-web.media.picture name="home/concept-2" :widths="[192, 320]" sizes="(min-width: 64rem) 160px, 96px" alt="" :width="192" :height="192" class="size-full object-cover" />
            </div>
            <div class="absolute overflow-hidden hidden -rotate-2 rounded bg-linear-to-br from-paper to-sand shadow-[0_34px_60px_-34px_rgb(60_40_20/0.55)] lg:top-[11.875rem] lg:right-[20%] lg:block lg:h-[13.75rem] lg:w-[10.9375rem]">
                <x-web.media.picture name="home/concept-3" :widths="[200, 400]" sizes="175px" alt="" :width="200" :height="250" class="size-full object-cover" />
            </div>
            <div class="absolute overflow-hidden top-[19.5rem] right-[6%] h-[12.5rem] w-[42%] rotate-3 rounded bg-linear-to-br from-honey to-sand shadow-[0_34px_60px_-34px_rgb(60_40_20/0.55)] lg:top-4 lg:right-[3%] lg:h-[18.75rem] lg:w-60 lg:rotate-4">
                <x-web.media.picture name="home/concept-4" :widths="[280, 560]" sizes="(min-width: 64rem) 240px, 42vw" alt="" :width="280" :height="350" class="size-full object-cover" />
            </div>
        </div>
    </div>
</section>

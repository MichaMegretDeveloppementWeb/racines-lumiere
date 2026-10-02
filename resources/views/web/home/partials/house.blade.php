<section aria-labelledby="house-title" class="mx-2 overflow-hidden rounded-3xl bg-paper px-6 py-20 lg:mx-3.5 lg:rounded-[2rem] lg:py-36">
    <div class="mx-auto grid max-w-[77.5rem] items-center gap-12 lg:grid-cols-[1fr_1.05fr] lg:gap-20 lg:px-10">
        <div aria-hidden="true" class="relative mx-auto h-[27.5rem] w-[21.25rem] lg:mx-0 lg:h-[40rem] lg:w-[32.5rem]">
            <svg viewBox="0 0 520 640" fill="none" class="absolute inset-0 size-full text-sand">
                <ellipse cx="240" cy="300" rx="236" ry="296" stroke="currentColor" transform="rotate(-8 240 300)" />
                <use href="#icon-sun" x="458" y="110" width="20" height="20" class="text-gold" />
            </svg>
            <div class="absolute top-2.5 left-1.5 h-[23rem] w-[17rem] overflow-hidden rounded-[50%] bg-linear-to-br from-honey via-sand to-paper lg:top-5 lg:left-[1.875rem] lg:h-[35rem] lg:w-[26.25rem]">
                <x-web.media.picture name="home/house-1" :widths="[440, 880]" sizes="(min-width: 64rem) 420px, 272px" alt="" :width="440" :height="587" class="size-full object-cover" />
            </div>
            <div class="absolute bottom-0 left-[11.5rem] size-[9.5rem] overflow-hidden rounded-full border-[7px] border-paper bg-linear-to-br from-sand to-honey lg:left-[20.625rem] lg:size-[13.125rem] lg:border-[9px]">
                <x-web.media.picture name="home/house-2" :widths="[240, 420]" sizes="(min-width: 64rem) 210px, 152px" alt="" :width="240" :height="240" class="size-full object-cover" />
            </div>
        </div>

        <div>
            <x-web.typography.eyebrow>Notre maison</x-web.typography.eyebrow>
            <h2 id="house-title" class="mt-6 text-2xl leading-[1.24] lg:text-[2.375rem]">Un luxe discret qui<br> laisse <span class="text-terracotta">la place au vrai.</span></h2>
            <p class="mt-7 leading-[1.85] text-olive">Massages enveloppants, rebozo, sonothérapie, diapasons, gestes experts du visage&nbsp;: tout se mêle dans une expérience sensorielle où le corps a toute sa place.</p>
            <p class="mt-4 leading-[1.85] text-olive">Des pépites skincare choisies avec exigence, une lumière douce.</p>
            <x-web.navigation.pill-link :href="route('story')" class="mt-11">Découvrir notre histoire</x-web.navigation.pill-link>
        </div>
    </div>
</section>

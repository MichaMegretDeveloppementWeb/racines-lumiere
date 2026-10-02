<section aria-labelledby="opening-banner-title" class="mx-2 mt-2 rounded-3xl bg-paper px-6 py-14 lg:mx-3.5 lg:mt-3.5 lg:rounded-[2rem] lg:px-16 lg:py-20">
    <div @class([
        'mx-auto grid max-w-[77.5rem] items-center gap-10',
        'lg:grid-cols-[1.2fr_1fr] lg:gap-20' => $institute->launchOffer !== null,
        'justify-items-center text-center' => $institute->launchOffer === null,
    ])>
        <div>
            <x-web.media.icon name="sun" @class(['size-7 text-gold', 'mx-auto' => $institute->launchOffer === null]) />
            <h2 id="opening-banner-title" @class(['mt-6 max-w-[24ch] text-[1.65rem] leading-snug lg:text-[2.5rem] lg:leading-tight', 'mx-auto' => $institute->launchOffer === null])>Prochainement, l'ouverture de notre Maison du <span class="whitespace-nowrap">Mieux-Être&nbsp;!</span></h2>
            <p @class(['mt-5 max-w-[46ch] leading-relaxed text-olive lg:text-lg', 'mx-auto' => $institute->launchOffer === null])>Racines &amp; Lumière est sur la fin des préparatifs pour pouvoir vous accueillir à partir du mardi 3 novembre&nbsp;!</p>
        </div>

        <x-web.institute.launch-offer />
    </div>
</section>

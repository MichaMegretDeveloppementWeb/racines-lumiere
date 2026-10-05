<section aria-labelledby="place-title" class="rl-section rl-place">
    <div class="rl-wrap rl-place-heading">
        <h2 id="place-title" class="rl-title">L'esprit du lieu</h2>
        <p>Béton ciré, pierre, lin et touches de bois, sous une lumière chaude et douce&nbsp;: une maison végétale, minérale et épurée, pensée pour que l'on s'y pose.</p>
    </div>
    <ul class="rl-wrap rl-place-materials" role="list">
        @foreach (['place-1' => 'Le lin', 'place-2' => 'Le béton ciré', 'place-3' => 'La pierre', 'place-4' => 'Le bois'] as $image => $material)
            <li>
                <figure>
                    <div class="rl-place-image">
                        <x-web.media.picture :name="'story/'.$image" :widths="[320, 640]" sizes="(min-width: 80rem) 290px, (min-width: 48rem) 22vw, 44vw" alt="" :width="320" :height="427" class="size-full object-cover" />
                    </div>
                    <figcaption>{{ $material }}</figcaption>
                </figure>
            </li>
        @endforeach
    </ul>
</section>

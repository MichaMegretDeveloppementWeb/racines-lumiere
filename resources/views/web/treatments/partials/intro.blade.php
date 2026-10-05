<section aria-labelledby="menu-title" class="rl-panel rl-panel-wide rl-menu-intro">
    <div class="rl-menu-intro-texture" aria-hidden="true">
        <x-web.media.picture name="home/linen" :widths="[480, 960]" sizes="100vw" alt="" :width="480" :height="320" is-priority class="size-full object-cover" />
    </div>
    <div class="rl-wrap rl-menu-intro-inner">
        <h1 id="menu-title">Nos soins</h1>
        <p class="rl-menu-intro-signature">Vous ne choisissez pas votre soin.<br>Nous le créons avec vous.</p>
        <nav aria-label="Catégories de la carte" class="rl-menu-index">
            <ol>
                @foreach ($menu->categories() as $category)
                    <li><a href="#{{ $category->slug }}"><span aria-hidden="true">{{ sprintf('%02d', $category->number) }}</span>{{ $category->name }}</a></li>
                @endforeach
            </ol>
        </nav>
    </div>
</section>

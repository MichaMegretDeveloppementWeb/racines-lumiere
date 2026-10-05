<x-web.layout.page-intro labelledby="menu-title">
    <h1 id="menu-title">Nos soins</h1>
    <p class="rl-page-intro-lead">Vous ne choisissez pas votre soin.<br>Nous le créons avec vous.</p>
    <nav aria-label="Catégories de la carte" class="rl-menu-index">
        <ol>
            @foreach ($menu->categories() as $category)
                <li><a href="#{{ $category->slug }}"><span aria-hidden="true">{{ sprintf('%02d', $category->number) }}</span>{{ $category->name }}</a></li>
            @endforeach
        </ol>
    </nav>
</x-web.layout.page-intro>

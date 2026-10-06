<x-web.layout.page-intro labelledby="menu-title" class="rl-menu-intro">
    <div class="rl-menu-intro-heading">
        <h1 id="menu-title">Nos soins</h1>
        <p class="rl-page-intro-lead">Vous ne choisissez pas votre soin.<br>Nous le créons avec vous.</p>
    </div>
    <div class="rl-menu-intro-picture" aria-hidden="true">
        <x-web.media.picture name="home/concept" :widths="[480, 960]" sizes="(min-width: 48rem) 65vw, 100vw" alt="" :width="480" :height="600" is-priority class="size-full object-cover" />
    </div>
</x-web.layout.page-intro>
<nav aria-label="Catégories de la carte" class="rl-wrap rl-menu-index">
    <ol>
        @foreach ($menu->categories() as $category)
            <li><a href="#{{ $category->slug }}">{{ $category->name }}<span aria-hidden="true">↓</span></a></li>
        @endforeach
    </ol>
</nav>

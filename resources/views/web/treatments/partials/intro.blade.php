<x-web.layout.page-intro labelledby="menu-title" breadcrumb="Nos soins" image-name="home/concept" :image-widths="[480, 960]" :image-width="480" :image-height="600" image-position="50% 75%" mobile-image-position="50% 35%">
    Nos soins
    <x-slot:lead>Votre rituel n'est pas écrit,<br>nous le créons avec vous.</x-slot:lead>
</x-web.layout.page-intro>
<nav aria-label="Catégories de la carte" class="rl-wrap rl-menu-index">
    <ol>
        @foreach ($menu->categories() as $category)
            <li><a href="#{{ $category->slug }}">{{ $category->name }}<span aria-hidden="true">↓</span></a></li>
        @endforeach
    </ol>
</nav>

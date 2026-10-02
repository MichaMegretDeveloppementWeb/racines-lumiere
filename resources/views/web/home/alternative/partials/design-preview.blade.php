{{-- Temporary local-only design comparison. Remove this partial and its matching assets before publication. --}}
@php
    $previewImages = [
        ['id' => 'original', 'label' => 'Originale', 'name' => 'hero-v3', 'widths' => [480, 960, 1440]],
        ['id' => 'golden', 'label' => 'Dorée', 'name' => 'preview-hero-golden', 'widths' => [480, 960, 1440]],
        ['id' => 'care', 'label' => 'Le geste', 'name' => 'preview-hero-care', 'widths' => [480, 960, 1440]],
        ['id' => 'botanical', 'label' => 'Botanique', 'name' => 'preview-hero-botanical', 'widths' => [480, 960, 1440]],
        ['id' => 'linen', 'label' => 'Le lin', 'name' => 'linen-v3', 'widths' => [480, 960]],
    ];
    foreach ($previewImages as &$previewImage) {
        $previewImage['src'] = asset('images/home/alternative/'.$previewImage['name'].'-480w.jpg');
        $previewImage['thumbnail'] = asset('images/home/alternative/'.$previewImage['name'].'-480w.webp');
        foreach (['avif', 'webp', 'jpg'] as $format) {
            $previewImage['srcsets'][$format] = implode(', ', array_map(
                fn (int $width): string => asset('images/home/alternative/'.$previewImage['name']."-{$width}w.{$format}")." {$width}w",
                $previewImage['widths'],
            ));
        }
    }
    unset($previewImage);
    $previewFonts = [
        ['id' => 'mulish', 'label' => 'Mulish', 'family' => "'Mulish', ui-sans-serif, system-ui, sans-serif"],
        ['id' => 'lora', 'label' => 'Lora', 'family' => "'Lora', Georgia, serif"],
        ['id' => 'jost', 'label' => 'Jost', 'family' => "'Jost', ui-sans-serif, system-ui, sans-serif"],
    ];
    $previewTones = [
        ['id' => 'clear', 'label' => 'Claire', 'filter' => 'none'],
        ['id' => 'nuanced', 'label' => 'Nuancée', 'filter' => 'brightness(0.94) saturate(1.04)'],
        ['id' => 'soft', 'label' => 'Feutrée', 'filter' => 'brightness(0.84) saturate(1.08)'],
    ];
    $previewHeights = [
        ['id' => 'compact', 'label' => 'Compacte', 'height' => '37rem'],
        ['id' => 'balanced', 'label' => 'Aérée', 'height' => 'max(40rem, 55vh)'],
        ['id' => 'generous', 'label' => 'Ample', 'height' => 'max(42rem, 65vh)'],
    ];
    $previewDefaults = ['imageId' => 'original', 'fontId' => 'mulish', 'toneId' => 'nuanced', 'heightId' => 'balanced'];
@endphp

<aside class="alt-preview" aria-label="Personnalisation de l'aperçu" x-cloak
    x-data="designPreview({{ Js::from(['images' => $previewImages, 'fonts' => $previewFonts, 'tones' => $previewTones, 'heights' => $previewHeights, 'defaults' => $previewDefaults]) }})"
    @keydown.escape.window="close" @click.outside="close">
    <div id="design-preview-panel" class="alt-preview-panel" x-show="isOpen" role="region" aria-labelledby="design-preview-title">
        <div class="alt-preview-heading">
            <h2 id="design-preview-title">Personnaliser l'aperçu</h2>
            <button type="button" class="alt-preview-close" aria-label="Replier les réglages" @click="close">
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="1.25" /></svg>
            </button>
        </div>

        <fieldset>
            <legend>Image du hero</legend>
            <div class="alt-preview-images">
                @foreach ($previewImages as $previewImage)
                    <label class="alt-preview-image-option">
                        <input type="radio" name="preview-image" value="{{ $previewImage['id'] }}" x-model="imageId" @change="selectImage">
                        <img src="{{ $previewImage['thumbnail'] }}" alt="" width="96" height="64" loading="lazy" decoding="async">
                        <span>{{ $previewImage['label'] }}</span>
                    </label>
                @endforeach
            </div>
            <p class="alt-preview-status" role="status" x-show="isLoading">L'image se prépare…</p>
            <p class="alt-preview-status" role="alert" x-show="hasImageError">L'image n'a pas pu être chargée. Réessayez.</p>
        </fieldset>

        <fieldset>
            <legend>Tonalité de l'image</legend>
            <div class="alt-preview-options">
                @foreach ($previewTones as $previewTone)
                    <label>
                        <input type="radio" name="preview-tone" value="{{ $previewTone['id'] }}" x-model="toneId" @change="selectTone">
                        <span>{{ $previewTone['label'] }}</span>
                    </label>
                @endforeach
            </div>
        </fieldset>

        <fieldset>
            <legend>Police du texte</legend>
            <div class="alt-preview-options">
                @foreach ($previewFonts as $previewFont)
                    <label>
                        <input type="radio" name="preview-font" value="{{ $previewFont['id'] }}" x-model="fontId" @change="selectFont">
                        <span style="font-family: {{ $previewFont['family'] }}">{{ $previewFont['label'] }}</span>
                    </label>
                @endforeach
            </div>
            <p class="alt-preview-note">Les titres restent en Cinzel.</p>
        </fieldset>

        <fieldset class="alt-preview-height">
            <legend>Hauteur du hero sur grand écran</legend>
            <div class="alt-preview-options">
                @foreach ($previewHeights as $previewHeight)
                    <label>
                        <input type="radio" name="preview-height" value="{{ $previewHeight['id'] }}" x-model="heightId" @change="selectHeight">
                        <span>{{ $previewHeight['label'] }}</span>
                    </label>
                @endforeach
            </div>
        </fieldset>

        <div class="alt-preview-bottom">
            <button type="button" @click="reset">Réinitialiser</button>
        </div>
    </div>
    <button type="button" class="alt-preview-toggle" x-ref="toggle" aria-controls="design-preview-panel" :aria-expanded="isOpen" @click="toggle">
        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="M4 7h16M4 17h16M8 4v6m8 4v6" stroke="currentColor" stroke-width="1.25" /></svg>
        <span>Personnaliser</span>
    </button>
</aside>

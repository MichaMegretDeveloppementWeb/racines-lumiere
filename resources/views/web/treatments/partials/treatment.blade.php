<li class="rl-treatment">
    <{{ $headingLevel ?? 'h3' }} class="rl-treatment-name">{{ $treatment->name }}</{{ $headingLevel ?? 'h3' }}>
    @if ($treatment->subtitle !== null)
        <p class="rl-treatment-subtitle">{{ $treatment->subtitle }}</p>
    @endif
    @if ($treatment->description !== null)
        <p class="rl-treatment-description">{{ $treatment->description }}</p>
    @endif
    <ul class="rl-variants">
        @foreach ($treatment->variants as $variant)
            <li class="rl-variant">
                <span class="rl-variant-meta">
                    @if ($variant->totalDuration !== null)
                        <strong>{{ $variant->totalDuration->label() }}</strong>
                    @endif
                    @if ($variant->label !== null)
                        <span>{{ $variant->label }}</span>
                    @endif
                    @if ($variant->careDuration !== null)
                        <span class="rl-variant-care">dont {{ $variant->careDuration->label() }} de soin</span>
                    @endif
                </span>
                <span class="rl-variant-price">{{ $variant->price->label() }}</span>
            </li>
        @endforeach
    </ul>
</li>

@props(['icon', 'color' => '', 'value', 'label'])

<div class="stat-card {{ $color }}">
    <div class="icon"><i class="bi {{ $icon }}"></i></div>
    <h3>{{ $value }}</h3>
    <p>{{ $label }}</p>
</div>

@props([
    'name' => 'sports_soccer',
    'size' => 'md', // xs, sm, md, lg, xl
    'filled' => false,
    'class' => '',
])

@php
    // Map friendly football names to Material Symbols
    $iconMap = [
        'home' => 'home',
        'trending' => 'trending_up',
        'communities' => 'groups',
        'match-room' => 'sports_soccer',
        'profile' => 'person',
        'search' => 'search',
        'bell' => 'notifications',
        'heart' => 'favorite',
        'comment' => 'chat_bubble',
        'share' => 'share',
        'settings' => 'settings',
        'menu' => 'menu',
        'close' => 'close',
        'pitch' => 'sports_soccer',
        'ball' => 'sports_soccer',
        'jersey' => 'sports_jersey',
        'trophy' => 'sports_trophy',
        'whistle' => 'sports_bar',
        'team' => 'groups',
        'player' => 'person',
        'coach' => 'school',
        'flag' => 'flag',
        'clock' => 'schedule',
        'calendar' => 'event',
        'location' => 'location_on',
        'link' => 'link',
        'external' => 'open_in_new',
        'download' => 'download',
        'upload' => 'upload',
        'edit' => 'edit',
        'delete' => 'delete',
        'add' => 'add',
        'back' => 'arrow_back',
        'forward' => 'arrow_forward',
        'up' => 'arrow_upward',
        'down' => 'arrow_downward',
        'more' => 'more_vert',
        'filter' => 'tune',
        'sort' => 'sort',
        'view-list' => 'view_list',
        'view-grid' => 'grid_view',
        'star' => 'star',
        'verified' => 'verified',
        'info' => 'info',
        'help' => 'help',
        'error' => 'error',
        'warning' => 'warning',
        'success' => 'check_circle',
    ];
    
    $sizeClasses = match($size) {
        'xs' => 'text-xs leading-none',
        'sm' => 'text-sm leading-none',
        'lg' => 'text-2xl leading-none',
        'xl' => 'text-3xl leading-none',
        default => 'text-lg leading-none',
    };
    
    // Resolve icon name from map or use directly
    $iconName = $iconMap[$name] ?? $name;
    
    // Add Material Symbols class
    $iconClass = 'material-symbols-' . ($filled ? 'filled' : 'outlined') . ' ' . $sizeClasses;
@endphp

<span 
    {{ $attributes->merge([
        'class' => $iconClass . ' ' . $class,
        'role' => 'img',
        'aria-hidden' => 'true',
    ]) }}
>
    {{ $iconName }}
</span>

@props(['withInstallation'])

<span {{ $attributes->class([
    'install-badge',
    'install-badge--yes' => $withInstallation,
    'install-badge--no' => ! $withInstallation,
]) }}>
    {{ $withInstallation ? 'მონტაჟით' : 'მონტაჟის გარეშე' }}
</span>

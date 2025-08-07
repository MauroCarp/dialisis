<x-filament-panels::page>
    @if (session('error'))
        <x-filament::notification 
            icon="heroicon-o-x-circle"
            title="Error de autenticación"
            :body="session('error')"
            color="danger"
        />
    @endif
</x-filament-panels::page>

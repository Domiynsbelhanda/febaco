<x-filament::page>

    <x-filament::button
        tag="a"
        href="{{ route('filament.admin.resources.teams.edit', ['record' => $record]) }}"
        color="gray"
        icon="heroicon-o-arrow-left">
        Retour à l'équipe
    </x-filament::button>


    <h2 class="text-2xl font-bold mb-4">Transferts de l’équipe</h2>
    {{ $this->table }}
</x-filament::page>

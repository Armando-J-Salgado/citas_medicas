<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Calendario de Citas</x-slot>

        <div
            x-data
            x-init="
                setTimeout(() => {
                    const calendarEl = document.getElementById('appointments-calendar');

                    if (!calendarEl) return;

                    if (typeof FullCalendar === 'undefined') {
                        console.error('FullCalendar no está cargado');
                        return;
                    }

                    const calendar = new FullCalendar.Calendar(calendarEl, {
                        locale: 'es',
                        initialView: 'dayGridMonth',
                        events: {{ Js::from($appointments) }},
                        height: 'auto',
                    });

                    calendar.render();
                }, 500);
            "
            wire:ignore
            id="appointments-calendar"
            style="min-height: 600px;"
        ></div>

    </x-filament::section>
</x-filament-widgets::widget>
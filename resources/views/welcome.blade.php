<html class="h-full">
    <script src="https://cdn.tailwindcss.com"></script>
    @livewire(App\Http\Livewire\Counter::class)

    <script>
        document.querySelectorAll('[wire\\:snapshot]').forEach( el => {
            let snapshot = JSON.parse(el.getAttribute('wire:snapshot'));
            console.log(snapshot);
        })
    </script>
</html>

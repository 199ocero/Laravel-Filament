<div class="mx-4">
    @if ($getState() == 'Active')
        <span class="px-4 py-2 bg-green-500">
            {{ $getState() }}
        </span>
    @else
        <span class="px-4 py-2 bg-red-500">
            {{ $getState() }}
        </span>
    @endif

</div>
<style>
    .bg-green-500 {
        background-color: #16A34A;
        border-radius: 0.5rem;
        font-size: 0.9rem
    }

    .bg-red-500 {
        background-color: #EF4444;
        border-radius: 0.5rem;
        font-size: 0.9rem
    }

    .mx-4 {
        margin-left: 1rem;
        margin-right: 1rem;
    }

    .px-4 {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .py-2 {
        padding-top: 0.2rem;
        padding-bottom: 0.2rem;
    }
</style>

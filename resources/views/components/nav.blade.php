<nav>
    @if (Auth::user())
        <Form wire:submit.prevent=''>
            <button>logout</button>
        </Form>
    @else
        <a href="#" wire:navigate>login</a>
        <a href="#" wire:navigate>register</a>
    @endif
</nav>
import './bootstrap';

import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import Clipboard from "@ryangjchandler/alpine-clipboard"
import Splide from 'alpine-splide'

Alpine.plugin(Clipboard)

Alpine.data('Splide', Splide)

// window.Alpine = Alpine
// window.Alpine.start()
Livewire.start()

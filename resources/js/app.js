import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.css';

// window.Alpine = Alpine;
// window.Livewire = Livewire;
window.TomSelect = TomSelect;
Livewire.start();

import './common';
import './department';
import './designation';

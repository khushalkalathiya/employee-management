import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.css';import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

// window.Alpine = Alpine;
// window.Livewire = Livewire;
window.TomSelect = TomSelect;
window.flatpickr = flatpickr;
Livewire.start();

import './common';
import './department';
import './designation';
import './leave-type';
import './holiday';
import './leave';
import './settings';

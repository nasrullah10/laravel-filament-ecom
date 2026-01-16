import './bootstrap';
import '../css/app.css';
import 'preline';

document.addEventListener('livewire:navigated', () => { 
    window.HSStaticMethods.autoInit();
})

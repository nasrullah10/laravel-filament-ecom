import './bootstrap';
import '../css/app.css';
import 'preline';

document.addEventListener('livewire:navigated', () => { 
    window.HSStaticMethods.autoInit();
})
// resources/js/app.js me add karein ya direct blade me

document.addEventListener('DOMContentLoaded', function() {
    // Video play button click
    document.querySelectorAll('.video-play-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const video = this.previousElementSibling;
            const icon = this.querySelector('div');
            
            if (video.paused) {
                // Pehle sab videos pause karein
                document.querySelectorAll('video').forEach(v => {
                    v.pause();
                    v.closest('.relative')?.querySelector('.video-play-btn')?.classList.remove('hidden');
                });
                
                video.play();
                this.classList.add('hidden'); // Play button hide
            }
        });
    });

    // Video end pe play button wapas show
    document.querySelectorAll('video').forEach(video => {
        video.addEventListener('ended', function() {
            const btn = this.nextElementSibling;
            btn.classList.remove('hidden');
        });
    });
});
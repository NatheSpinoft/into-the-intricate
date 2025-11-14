function startClock() {
    const clock = document.getElementById("clock");

    function update() {
        const now = new Date();
        let hh = now.getHours();
        const mm = String(now.getMinutes()).padStart(2, '0');
        const ss = String(now.getSeconds()).padStart(2, '0');
        
        const ampm = hh >= 12 ? 'PM' : 'AM';
        hh = hh % 12;           // convert to 12-hour
        hh = hh ? hh : 12;      // the hour '0' should be '12'

        clock.textContent = `${hh}:${mm}:${ss} ${ampm}`;
    }

    update(); 
    setInterval(update, 1000);
}

startClock();

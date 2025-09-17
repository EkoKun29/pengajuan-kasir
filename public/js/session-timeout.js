// Inisialisasi timer
let inactivityTime = function() {
    let time;
    const logoutTime = 10 * 60 * 1000; // 10 menit dalam milidetik
    const warningTime = 9 * 60 * 1000; // 9 menit, memberikan peringatan 1 menit sebelum logout
    let warningShown = false;
    
    // Events yang akan reset timer
    window.onload = resetTimer;
    document.onmousemove = resetTimer;
    document.onkeydown = resetTimer;
    document.onclick = resetTimer;
    document.onscroll = resetTimer;
    document.onwheel = resetTimer;
    
    function resetTimer() {
        clearTimeout(time);
        
        // Reset status peringatan
        if (warningShown) {
            const warningElement = document.getElementById('session-timeout-warning');
            if (warningElement) {
                warningElement.remove();
            }
            warningShown = false;
        }
        
        // Set timer baru
        time = setTimeout(() => {
            if (!warningShown) {
                showWarning();
            }
        }, warningTime);
        
        // Set timer untuk logout
        setTimeout(() => {
            // Submit form logout secara otomatis dengan CSRF token
            const logoutForm = document.getElementById('logout-form');
            if (logoutForm) {
                logoutForm.submit();
            } else {
                // Jika tidak ada form logout, buat form sementara dan submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/logout';
                form.style.display = 'none';
                
                // Cari csrf-token dari meta tag
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                if (token) {
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = token;
                    form.appendChild(csrfInput);
                }
                
                document.body.appendChild(form);
                form.submit();
            }
        }, logoutTime);
    }
    
    function showWarning() {
        warningShown = true;
        
        // Buat elemen peringatan
        const warningDiv = document.createElement('div');
        warningDiv.id = 'session-timeout-warning';
        warningDiv.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4';
        
        const content = `
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-yellow-100 mb-4">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Peringatan Sesi Akan Berakhir</h3>
                    <p class="mb-4 text-gray-600">Sesi Anda akan berakhir dalam 1 menit karena tidak ada aktivitas.</p>
                    <div class="flex justify-center space-x-3">
                        <button onclick="window.location.reload()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            Tetap Masuk
                        </button>
                        <button onclick="document.getElementById('logout-form').submit()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        warningDiv.innerHTML = content;
        document.body.appendChild(warningDiv);
    }
};

// Jalankan fungsi
window.onload = function() {
    inactivityTime();
};
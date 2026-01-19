<div id="confirm-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeConfirmModal()"></div>
    
    <!-- Modal -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded shadow-elegant max-w-md w-full p-6 transform transition-all">
            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            
            <!-- Content -->
            <div class="text-center">
                <h3 class="text-lg font-display font-semibold text-primary-dark mb-2">
                    Confirmar Acción
                </h3>
                <p id="confirm-message" class="text-sm text-gray-600 mb-6">
                    ¿Estás seguro de realizar esta acción?
                </p>
            </div>
            
            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-3">
                <button onclick="closeConfirmModal()" class="btn-secondary flex-1">
                    Cancelar
                </button>
                <button id="confirm-button" class="btn-primary flex-1 bg-red-600 hover:bg-red-700">
                    Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let confirmCallback = null;
    
    function showConfirmModal(message, callback) {
        document.getElementById('confirm-message').textContent = message;
        document.getElementById('confirm-modal').classList.remove('hidden');
        confirmCallback = callback;
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }
    
    function closeConfirmModal() {
        document.getElementById('confirm-modal').classList.add('hidden');
        confirmCallback = null;
        document.body.style.overflow = '';
    }
    
    document.getElementById('confirm-button').addEventListener('click', function() {
        if (confirmCallback) {
            confirmCallback();
        }
        closeConfirmModal();
    });
</script>
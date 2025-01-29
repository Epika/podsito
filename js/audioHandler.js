class AudioManager {
    constructor() {
        this.fileInput = document.getElementById('audioFile');
        this.form = document.getElementById('uploadForm');
        this.audioList = document.getElementById('audioList');
        this.init();
    }

    async subirArchivos() {
        if (!this.fileInput?.files?.length) {
            M.toast({
                html: 'Seleccione archivos primero',
                classes: 'red'
            });
            return;
        }

        const formData = new FormData();
        Array.from(this.fileInput.files).forEach(file => {
            formData.append('audio[]', file);
        });

        try {
            const response = await fetch('../upload.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            if (result.success) {
                M.toast({
                    html: '¡Archivo subido correctamente!',
                    classes: 'green',
                    displayLength: 3000
                });
                
                // Mostrar el reproductor de audio
                this.mostrarReproductor(this.fileInput.files[0].name, result.path);
                
                this.form?.reset();
            } else {
                throw new Error(result.error || 'Error al subir archivos');
            }
        } catch (error) {
            console.error('Error:', error);
            M.toast({
                html: 'Tu archivo no se subió',
                classes: 'red',
                displayLength: 3000
            });
        }
    }

    mostrarReproductor(nombre, ruta) {
        if (this.audioList) {
            const audioPlayer = `
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">${nombre}</span>
                        <audio controls class="width-100">
                            <source src="${ruta}" type="audio/mpeg">
                            Tu navegador no soporta audio
                        </audio>
                    </div>
                </div>
            `;
            this.audioList.insertAdjacentHTML('afterbegin', audioPlayer);
        }
    }

    init() {
        if (this.form) {
            this.form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.subirArchivos();
            });
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new AudioManager();
});
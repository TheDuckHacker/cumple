document.addEventListener('DOMContentLoaded', function () {
  const flame = document.querySelector('.flame');
  const message = document.getElementById('birthday-message');
  let opacity = 0.9;

  // Manejar reproducción de audio al hacer clic en cualquier parte de la página
  document.addEventListener('click', function () {
    const audio = document.getElementById('background-audio');
    audio.play()
      .then(() => {
        console.log('Audio iniciado');
      })
      .catch((error) => {
        console.error('Error al reproducir el audio:', error);
      });
  });

  function showConfetti() {
    confetti({
      particleCount: 80,
      spread: 70,
      origin: { y: 0.6 },
      colors: ['#ff0000', '#00ff00', '#0000ff', '#ffff00', '#ff00ff', '#00ffff'],
    });
  }

  function startMicrophoneDetection() {
    navigator.mediaDevices.getUserMedia({ audio: true })
      .then(function (stream) {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const microphone = audioContext.createMediaStreamSource(stream);
        const analyser = audioContext.createAnalyser();

        analyser.fftSize = 256;
        microphone.connect(analyser);

        const dataArray = new Uint8Array(analyser.frequencyBinCount);

        function detectBlow() {
          analyser.getByteFrequencyData(dataArray);
          const averageVolume = dataArray.reduce((sum, value) => sum + value, 0) / dataArray.length;

          // Detecta un volumen alto como indicativo de soplido
          if (averageVolume > 80) {
            opacity -= 0.05;
            if (opacity < 0) opacity = 0;
            flame.style.opacity = opacity;

            // Muestra el mensaje cuando la llama se apaga
            if (opacity === 0) {
              message.style.display = 'block';
              showConfetti(); // Llama a la función de confeti
            }
          }

          requestAnimationFrame(detectBlow);
        }

        detectBlow();
      })
      .catch(function (err) {
        console.error('Error al acceder al micrófono:', err);
      });
  }

  startMicrophoneDetection();
});

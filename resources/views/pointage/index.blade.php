@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center font-bold text-lg mb-4">Pointage ANAM</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
        <button class="bg-green-500 text-white p-4 rounded w-full" onclick="startScanner('entrée')">Entrée</button>
        <button class="bg-red-500 text-white p-4 rounded w-full" onclick="startScanner('descente')">Descente</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-center mt-4">
        <button class="bg-yellow-500 text-white p-4 rounded w-full" onclick="startScanner('sortie_provisoire')">Sortie Provisoire</button>
        <button class="bg-blue-500 text-white p-4 rounded w-full" onclick="startScanner('retour_sortie')">Retour de sortie</button>
    </div>

    <video id="qr-video" style="width:100%; display:none;"></video>
</div>

<script src="https://unpkg.com/jsqr/dist/jsQR.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 -->

<script>
    function startScanner(type) {
        let video = document.getElementById('qr-video');
        video.style.display = "block";

        navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
            .then(stream => {
                console.log("✅ Caméra activée avec succès !");
                video.srcObject = stream;
                video.play();
                scanQRCode(video, type);
            })
            .catch(error => {
                console.error("❌ Erreur d'accès à la caméra :", error);
                Swal.fire("Erreur", "Impossible d'accéder à la caméra. Vérifiez les permissions.", "error");
            });
    }

    function scanQRCode(video, type, attempts = 10) {
        let canvas = document.createElement('canvas');
        let ctx = canvas.getContext('2d');

        function scan() {
            if (video.videoWidth === 0 || video.videoHeight === 0) {
                console.warn("⚠️ La caméra semble ne pas fournir d'image, tentative " + (11 - attempts));
                
                if (attempts > 0) {
                    setTimeout(() => requestAnimationFrame(() => scanQRCode(video, type, attempts - 1)), 500);
                    return;
                }

                Swal.fire("Erreur", "La caméra ne fonctionne pas correctement après plusieurs tentatives.", "error");
                return;
            }

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            let code = jsQR(imageData.data, canvas.width, canvas.height);

            if (code) {
                console.log("✅ QR Code détecté :", code.data);
                video.srcObject.getTracks().forEach(track => track.stop());
                video.style.display = "none";

                navigator.geolocation.getCurrentPosition(position => {
                    let latitude = position.coords.latitude;
                    let longitude = position.coords.longitude;

                    if (type === "sortie_provisoire") {
                        demanderMotifEtEnvoyer(type, code.data, latitude, longitude);
                    } else if (type === "retour_sortie") {
                        envoyerRetourSortie(code.data, latitude, longitude);
                    } else {
                        envoyerPointage(type, code.data, latitude, longitude, null);
                    }
                });
            } else {
                requestAnimationFrame(() => scanQRCode(video, type, attempts));
            }
        }

        scan();
    }

    function demanderMotifEtEnvoyer(type, qrCode, latitude, longitude) {
        Swal.fire({
            title: "Motif de sortie",
            input: "text",
            inputPlaceholder: "Entrez le motif de la sortie",
            showCancelButton: true,
            confirmButtonText: "Valider",
            cancelButtonText: "Annuler",
            inputValidator: (motif) => {
                if (!motif) {
                    return "Le motif est obligatoire !";
                }
            }
        }).then(result => {
            if (result.isConfirmed) {
                envoyerPointage(type, qrCode, latitude, longitude, result.value);
            }
        });
    }

    function envoyerPointage(type, qrCode, latitude, longitude, motif) {
        fetch('{{ route("pointage.store") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ type, latitude, longitude, qrCode, motif })
        })
        .then(response => response.json())
        .then(data => {
            console.log("✅ Réponse du serveur :", data);
            afficherAlerte(type);
        })
        .catch(error => {
            console.error("❌ Erreur d'enregistrement :", error);
            Swal.fire("Erreur", "Une erreur s'est produite lors de l'enregistrement.", "error");
        });
    }

    function envoyerRetourSortie(qrCode, latitude, longitude) {
        fetch('{{ route("pointage.retour") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ qrCode, latitude, longitude })
        })
        .then(response => response.json())
        .then(data => {
            console.log("✅ Retour enregistré :", data);
            afficherAlerte('retour_sortie');
        })
        .catch(error => {
            console.error("❌ Erreur retour de sortie :", error);
            Swal.fire("Erreur", "Impossible de valider le retour de sortie.", "error");
        });
    }

    function afficherAlerte(type) {
        let messages = {
            "entrée": { title: "Entrée Enregistrée", text: "Bonjour !", icon: "success" },
            "descente": { title: "Descente Enregistrée", text: "Au revoir !", icon: "success" },
            "sortie_provisoire": { title: "Sortie Provisoire Enregistrée", text: "", icon: "info" },
            "retour_sortie": { title: "Retour de Sortie Enregistré", text: "", icon: "info" }
        };

        let message = messages[type] || { title: "Action Réussie", text: "", icon: "success" };

        Swal.fire({
            title: message.title,
            text: message.text,
            icon: message.icon
        });
    }
</script>
@endsection

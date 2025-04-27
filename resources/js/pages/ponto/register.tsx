import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { useEffect, useRef, useState } from 'react';

import HeadingSmall from '@/components/heading-small';
import AppLayout from '@/layouts/app-layout';
import PontoLayout from '@/layouts/ponto/layout';

import { Button } from '@/components/ui/button';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Meu ponto',
        href: '/ponto',
    },
    {
        title: 'Registrar',
        href: '/ponto/registrar',
    },
];

export default function Ponto() {
    const videoRef = useRef<HTMLVideoElement>(null);
    const canvasRef = useRef<HTMLCanvasElement>(null);
    const [photo, setPhoto] = useState<string | null>(null);
    const [latitude, setLatitude] = useState<string | null>(null);
    const [longitude, setLongitude] = useState<string | null>(null);

    useEffect(() => {
        const startWebcam = async () => {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                if (videoRef.current) {
                    videoRef.current.srcObject = stream;
                }
            } catch (error) {
                console.error('Erro ao acessar a webcam:', error);
            }
        };

        const getLocation = () => {
            if (navigator.geolocation) {
                navigator.geolocation.watchPosition(
                    (position) => {
                        setLatitude(position.coords.latitude.toFixed(6));
                        setLongitude(position.coords.longitude.toFixed(6));
                    },
                    (error) => {
                        console.error('Erro ao obter localização:', error);
                    },
                );
            } else {
                console.error('Geolocalização não é suportada pelo navegador.');
            }
        };

        startWebcam();
        getLocation();

        return () => {
            // Cleanup: Parar o stream da webcam ao desmontar o componente
            if (videoRef.current && videoRef.current.srcObject) {
                const stream = videoRef.current.srcObject as MediaStream;
                stream.getTracks().forEach((track) => track.stop());
            }
        };
    }, []);

    const capturePhoto = () => {
        if (videoRef.current && canvasRef.current) {
            const canvas = canvasRef.current;
            const context = canvas.getContext('2d');
            if (context) {
                // Define o tamanho do canvas igual ao vídeo
                canvas.width = videoRef.current.videoWidth;
                canvas.height = videoRef.current.videoHeight;

                // Desenha o frame atual do vídeo no canvas
                context.drawImage(videoRef.current, 0, 0, canvas.width, canvas.height);

                // Converte o conteúdo do canvas para Base64
                const dataUrl = canvas.toDataURL('image/png');
                setPhoto(dataUrl);
            }
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Ponto" />

            <PontoLayout>
                <div className="space-y-6">
                    <HeadingSmall title="Registrar" description="Fazer o registro do seu ponto." />

                    <div className="webcam-container">
                        <video ref={videoRef} autoPlay playsInline className="w-full rounded-lg border border-gray-300" />
                    </div>

                    {/* Botão para capturar a foto */}
                    <Button className="w-full bg-green-800" onClick={capturePhoto}>
                        Capturar foto
                    </Button>

                    {/* Exibe a foto capturada */}
                    {photo && (
                        <div className="mt-4">
                            <p>Foto capturada:</p>
                            <img src={photo} alt="Foto capturada" className="rounded border border-gray-300" />
                        </div>
                    )}

                    {/* Exibe a localização geográfica */}
                    <div className="mt-4">
                        <h3 className="text-base font-medium">Localização atual:</h3>
                        <p>
                            <strong>Latitude:</strong> {latitude || 'Obtendo...'}
                        </p>
                        <p>
                            <strong>Longitude:</strong> {longitude || 'Obtendo...'}
                        </p>
                    </div>

                    {/* Canvas oculto para armazenar a imagem capturada */}
                    <canvas ref={canvasRef} style={{ display: 'none' }} />
                </div>
            </PontoLayout>
        </AppLayout>
    );
}

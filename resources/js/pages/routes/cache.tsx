import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage, useForm, Link } from '@inertiajs/react';
import { ArrowLeft, Trash2, RefreshCw, Info } from 'lucide-react';
import * as React from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Routes',
        href: '/routes',
    },
    {
        title: 'Detalhes do Cache',
        href: '/routes/cache',
    },
];

export default function CacheDetails() {
    const { isCached, lastCachedAt, flash } = usePage().props as any;
    const { post, delete: destroy, processing } = useForm();

    const handleGenerate = () => {
        post(route('routes.cache'));
    };

    const handleClear = () => {
        if (confirm('Tem certeza que deseja limpar o cache de rotas? Sem isso as rotas carregam dinamicamente por request, em ambiente de produção isto pode atrasar a performance significativamente.')) {
            destroy(route('routes.cache.clear'));
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Detalhes do Cache de Rotas" />
            <div className="flex h-full flex-1 flex-col mx-auto max-w-4xl gap-6 rounded-xl p-6">
                <div>
                    <Button variant="outline" asChild className="mb-6">
                        <Link href={route('routes')}>
                            <ArrowLeft className="mr-2 h-4 w-4" /> Voltar para Rotas
                        </Link>
                    </Button>

                    <h1 className="text-2xl font-bold mb-2">Gerenciamento Avançado do Cache de Rotas</h1>
                    <p className="text-muted-foreground text-sm">
                        Visualize e controle o arquivo flat compilado de rotas do Laravel.
                    </p>
                </div>

                <div className="grid gap-6 md:grid-cols-2">
                    {/* Status Card */}
                    <div className="rounded-xl border bg-card p-6 shadow-sm">
                        <h2 className="text-lg font-semibold flex items-center gap-2 mb-4">
                            <Info className="h-5 w-5 text-blue-500" /> Status Atual
                        </h2>
                        
                        <div className="space-y-4">
                            <div>
                                <p className="text-sm font-medium text-muted-foreground">Estado do Arquivo</p>
                                <div className="mt-1 flex items-center">
                                    <div className={`h-3 w-3 rounded-full mr-2 ${isCached ? 'bg-green-500' : 'bg-red-500'}`}></div>
                                    <span className="font-semibold">{isCached ? 'Cache Gerado (Ativo)' : 'Sem Cache (Runtime Dinâmico)'}</span>
                                </div>
                            </div>
                            
                            <div>
                                <p className="text-sm font-medium text-muted-foreground">Última Atualização</p>
                                <p className="mt-1 font-semibold">{lastCachedAt ? lastCachedAt : 'Nunca'}</p>
                            </div>
                        </div>

                        <div className="mt-8 flex flex-col gap-3">
                            <Button 
                                onClick={handleGenerate} 
                                disabled={processing} 
                                className="w-full flex items-center justify-center gap-2"
                            >
                                <RefreshCw className={`h-4 w-4 ${processing ? 'animate-spin' : ''}`} />
                                {isCached ? 'Regerar Cache' : 'Gerar Cache Agora'}
                            </Button>
                            
                            {isCached && (
                                <Button 
                                    onClick={handleClear} 
                                    disabled={processing} 
                                    variant="destructive" 
                                    className="w-full flex items-center justify-center gap-2"
                                >
                                    <Trash2 className="h-4 w-4" />
                                    Limpar Cache Existente
                                </Button>
                            )}
                        </div>
                    </div>

                    {/* Explainer Card */}
                    <div className="rounded-xl border bg-secondary/30 p-6 shadow-sm">
                        <h2 className="text-lg font-semibold mb-3">Como funciona o Cache de Rotas?</h2>
                        <div className="space-y-4 text-sm text-muted-foreground leading-relaxed">
                            <p>
                                O Laravel registra rotas lendo todos os arquivos de definição na pasta de rotas (web.php, api.php, etc). Em aplicações com dezenas ou centenas de endpoints, esse processo computacional repete-se em <strong>cada requisição.</strong>
                            </p>
                            <p>
                                Ao gerar o <strong>Cache de Rotas</strong>, a aplicação serializa o mapa completo e salva em disco como um array estático (geralmente em <code>bootstrap/cache/routes-v7.php</code>). A partir desse momento, o Laravel pula a etapa de compilar todas as instâncias novamente.
                            </p>
                            <div className="bg-orange-500/10 border-l-4 border-orange-500 text-orange-800 dark:text-orange-200 p-3 rounded">
                                <strong>Atenção:</strong> Sempre que você alterar, deletar ou criar novas rotas via código-fonte, será necessário limpar ou regerar o cache por aqui. Rotas em cache não refletem mudanças feitas no código!
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}

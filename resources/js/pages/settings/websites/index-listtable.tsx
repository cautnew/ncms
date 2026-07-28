import { Button } from '@/components/ui/button';
import { MoreHorizontal } from 'lucide-react';

export default function WebsitesIndexListTable({ websites_list }: { websites_list?: any[] }) {
    return (
        <div className="overflow-x-auto rounded-lg border">
            <table className="w-full text-sm">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Hostname</th>
                        <th>Usage</th>
                        <th>Articles</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {websites_list ? websites_list.map( website => (
                        <tr className="group cursor-pointer hover:bg-accent/50 data-[state=selected]:bg-accent">
                            <td className="px-4 py-2">{website.name}</td>
                            <td className="px-4 py-2">
                                <div className="flex w-full flex-col">
                                    <p className="truncate text-sm font-semibold leading-relaxed group-hover:text-accent-foreground">
                                        {website.hostname}
                                    </p>
                                    <a
                                        href="https://example.com"
                                        target="_blank"
                                        className="truncate text-xs text-muted-foreground hover:underline"
                                    >
                                        https://{website.domain}
                                    </a>
                                </div>
                            </td>
                            <td className="px-4 py-2">
                                <div className="flex w-full flex-col">
                                    <p className="truncate text-xs text-muted-foreground">
                                        2400 / 3000 requests
                                    </p>
                                    <div className="relative mt-0.5 h-1 w-full rounded-full bg-muted">
                                        <div
                                            className="absolute h-1 rounded-full bg-primary"
                                            style={{ width: '80%' }}
                                        />
                                    </div>
                                </div>
                            </td>
                            <td className="px-4 py-2">
                                <div className="flex w-full flex-col">
                                    <p className="truncate text-xs text-muted-foreground">
                                        2400 / 3000 requests
                                    </p>
                                    <div className="relative mt-0.5 h-1 w-full rounded-full bg-muted">
                                        <div
                                            className="absolute h-1 rounded-full bg-primary"
                                            style={{ width: '80%' }}
                                        />
                                    </div>
                                </div>
                            </td>
                            <td className="px-4 py-2 text-right">
                                <Button variant="ghost" className="h-8 w-8">
                                    <MoreHorizontal className="h-4 w-4" />
                                </Button>
                            </td>
                        </tr>
                    )) : (<tr><td col-span="5">Nada encontrado</td></tr>)}
                    <tr className="group cursor-pointer hover:bg-accent/50 data-[state=selected]:bg-accent">
                        <td className="px-4 py-2"></td>
                        <td className="px-4 py-2">
                            <div className="flex w-full flex-col">
                                <p className="truncate text-sm font-semibold leading-relaxed group-hover:text-accent-foreground">
                                    Example domain
                                </p>
                                <a
                                    href="https://example.com"
                                    target="_blank"
                                    className="truncate text-xs text-muted-foreground hover:underline"
                                >
                                    https://example.com
                                </a>
                            </div>
                        </td>
                        <td className="px-4 py-2">
                            <div className="flex w-full flex-col">
                                <p className="truncate text-xs text-muted-foreground">
                                    2400 / 3000 requests
                                </p>
                                <div className="relative mt-0.5 h-1 w-full rounded-full bg-muted">
                                    <div
                                        className="absolute h-1 rounded-full bg-primary"
                                        style={{ width: '80%' }}
                                    />
                                </div>
                            </div>
                        </td>
                        <td className="px-4 py-2">
                            <div className="flex w-full flex-col">
                                <p className="truncate text-xs text-muted-foreground">
                                    2400 / 3000 requests
                                </p>
                                <div className="relative mt-0.5 h-1 w-full rounded-full bg-muted">
                                    <div
                                        className="absolute h-1 rounded-full bg-primary"
                                        style={{ width: '80%' }}
                                    />
                                </div>
                            </div>
                        </td>
                        <td className="px-4 py-2 text-right">
                            <Button variant="ghost" className="h-8 w-8">
                                <MoreHorizontal className="h-4 w-4" />
                            </Button>
                        </td>
                    </tr>
                </tbody>
                <tfoot></tfoot>
            </table>
        </div>
    );
};
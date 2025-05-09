import VerticalTabsLayout from '@/layouts/verttabs-layout';
import { type NavItem } from '@/types';
import { Clock } from 'lucide-react';
import { type PropsWithChildren, ReactNode } from 'react';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Meu ponto',
        href: '/ncms/ponto',
        icon: Clock,
    },
    {
        title: 'Registrar',
        href: '/ncms/ponto/registrar',
        icon: null,
    },
];

export default function SettingsLayout({
    children,
}: PropsWithChildren<{
    children: ReactNode;
}>) {
    return (
        <VerticalTabsLayout sidebarNavItems={sidebarNavItems} title="Ponto" description="Registre e acompanhe seu inicio e fim de jornada.">
            {children}
        </VerticalTabsLayout>
    );
}

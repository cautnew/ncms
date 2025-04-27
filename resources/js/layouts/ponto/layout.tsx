import VerticalTabsLayout from '@/layouts/verttabs-layout';
import { type NavItem } from '@/types';
import { type PropsWithChildren, ReactNode } from 'react';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Meu ponto',
        href: '/ponto',
        icon: null,
    },
    {
        title: 'Registrar',
        href: '/ponto/registrar',
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

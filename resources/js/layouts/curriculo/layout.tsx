import VerticalTabsLayout from '@/layouts/verttabs-layout';
import { type NavItem } from '@/types';
import { Clock } from 'lucide-react';
import { type PropsWithChildren, ReactNode } from 'react';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Meus currículos',
        href: '/ncms/curriculos',
        icon: Clock,
    },
];

export default function SettingsLayout({
    children,
}: PropsWithChildren<{
    children: ReactNode;
}>) {
    return (
        <VerticalTabsLayout sidebarNavItems={sidebarNavItems} title="Meu Curriculo" description="Controle dos seus currículos.">
            {children}
        </VerticalTabsLayout>
    );
}

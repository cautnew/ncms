import VerticalTabsLayout from '@/layouts/verttabs-layout';
import { type NavItem } from '@/types';
import { type PropsWithChildren, ReactNode } from 'react';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Profile',
        href: '/ncms/settings/profile',
        icon: null,
    },
    {
        title: 'Password',
        href: '/ncms/settings/password',
        icon: null,
    },
    {
        title: 'Appearance',
        href: '/ncms/settings/appearance',
        icon: null,
    },
];

export default function SettingsLayout({
    children,
}: PropsWithChildren<{
    children: ReactNode;
}>) {
    return (
        <VerticalTabsLayout sidebarNavItems={sidebarNavItems} title="Settings" description="Manage your account settings">
            {children}
        </VerticalTabsLayout>
    );
}

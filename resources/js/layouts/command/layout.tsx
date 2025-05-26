import VerticalTabsLayout from '@/layouts/verttabs-layout';
import { type NavItem } from '@/types';
import { Clock } from 'lucide-react';
import { type PropsWithChildren, ReactNode } from 'react';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Commands',
        href: '/ncms/commands',
        icon: Clock,
    },
];

interface PropsWithChildren {
  children: ReactNode;
};

export default function CommandLayout({
    children
}: PropsWithChildren) {
    return (
        <VerticalTabsLayout sidebarNavItems={sidebarNavItems} title="Commands" description="Commands you can run, check status and logs.">
            {children}
        </VerticalTabsLayout>
    );
}

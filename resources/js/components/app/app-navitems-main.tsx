import { type NavItem } from '@/types';
import { BookCopy, CassetteTape, Layers, LayoutGrid, RouteIcon } from 'lucide-react';

export const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Pages',
        href: '/pages',
        icon: Layers,
    },
    {
        title: 'Routes',
        href: '/routes',
        icon: RouteIcon,
    },
    {
        title: 'Taxonomy',
        href: '/taxonomy',
        icon: BookCopy,
    },
    {
        title: 'Media',
        href: '/media',
        icon: CassetteTape,
    },
];

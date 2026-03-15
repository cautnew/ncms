import { type NavItem } from '@/types';
import { BookCopy, CassetteTape, Layers, LayoutGrid, RouteIcon, LucideIcon } from 'lucide-react';
import { usePage } from '@inertiajs/react';

const iconMap: Record<string, LucideIcon> = {
    LayoutGrid,
    Layers,
    CassetteTape,
    BookCopy,
    RouteIcon,
};

export function useMainNavItems(): NavItem[] {
    const page = usePage();
    const appNavItemsMain = (page.props.interfaceProperties as any)?.appNavItemsMain || [];

    return appNavItemsMain.map((item: any) => ({
        title: item.name,
        href: item.href,
        icon: iconMap[item.iconName] || null,
    }));
}

import { type NavItem } from '@/types';

export const sidebarNavItems: NavItem[] = [
    { title: 'Website', href: '/settings/website' },
    { title: 'Profile', href: '/settings/profile' },
    {
        title: 'Template',
        href: '/settings/template',
        subitems: [
            { title: 'Adjustments', href: '/settings/template/adjustments' },
            { title: 'List', href: '/settings/template/list' },
        ],
    },
    { title: 'Appearance', href: '/settings/appearance' },
    {
        title: 'Core',
        subitems: [
            { title: 'Request methods', href: '/settings/core/request-methods' },
            { title: 'Response types', href: '/settings/core/response-types' },
        ],
    },
];

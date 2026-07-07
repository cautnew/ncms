import { Link } from '@inertiajs/react';
import {
    BookOpen,
    FileText,
    FolderGit2,
    HelpCircle,
    LayoutGrid,
    Newspaper,
    ShoppingBag,
} from 'lucide-react';
import AppLogo from '@/components/app-logo';
import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { index as articlesIndex } from '@/routes/admin/articles';
import { index as faqCategoriesIndex } from '@/routes/admin/faq-categories';
import { home as pagesHome } from '@/routes/admin/pages';
import { index as productsIndex } from '@/routes/admin/products';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Artigos',
        href: articlesIndex(),
        icon: Newspaper,
    },
    {
        title: 'Produtos',
        href: productsIndex(),
        icon: ShoppingBag,
    },
    {
        title: 'Categorias de FAQ',
        href: faqCategoriesIndex(),
        icon: HelpCircle,
    },
    {
        title: 'Páginas',
        href: pagesHome(),
        icon: FileText,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/react-starter-kit',
        icon: FolderGit2,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#react',
        icon: BookOpen,
    },
];

export function AppSidebar() {
    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboard()} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}

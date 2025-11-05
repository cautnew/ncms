import { type BreadcrumbItem } from '@/types';
import { useEffect, useState, type ReactNode } from 'react';
import AppHeaderLayout from './app/app-header-layout';
import AppSidebarLayout from './app/app-sidebar-layout';

interface AppLayoutProps {
    children: ReactNode;
    breadcrumbs?: BreadcrumbItem[];
}

export default ({ children, breadcrumbs, ...props }: AppLayoutProps) => {
    const [isMobile, setIsMobile] = useState(window.innerWidth < 768);

    useEffect(() => {
        const handleResize = () => {
            setIsMobile(window.innerWidth < 768);
        };

        window.addEventListener('resize', handleResize);
        return () => {
            window.removeEventListener('resize', handleResize);
        };
    }, [isMobile]);

    const AppLayoutTemplate = isMobile ? AppHeaderLayout : AppSidebarLayout;

    return (
        <AppLayoutTemplate breadcrumbs={breadcrumbs} {...props}>
            {children}
        </AppLayoutTemplate>
    );
};

<script setup lang="ts">
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarGroupContent,
    SidebarGroupLabel,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    useSidebar,
} from "@//components/ui/sidebar";
import { MyTooltip } from "@//components/ui/tooltip";
import { Icon } from "@//components/ui/fa";
</script>

<template>
    <Sidebar collapsible="icon" class="print:hidden">
        <SidebarHeader />
        <SidebarContent>
            <SidebarGroup>
                <SidebarGroupLabel>Application</SidebarGroupLabel>
                <SidebarGroupContent>
                    <SidebarMenu>
                        <SidebarMenuItem>
                            <SidebarMenuButton class="text-md" asChild>
                                <a
                                    :href="route('ncms.dashboard')"
                                    title="Dashboard"
                                >
                                    <Icon as="faHome" class="me-2" />
                                    Dashboard
                                </a>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                        <SidebarMenuItem>
                            <SidebarMenuButton class="text-md" asChild>
                                <a
                                    :href="route('ncms.profile.edit')"
                                    title="Profile"
                                >
                                    <Icon as="faUser" class="me-2" />
                                    Profile
                                </a>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenu>
                </SidebarGroupContent>
            </SidebarGroup>
            <SidebarGroup>
                <SidebarGroupLabel>Configurations</SidebarGroupLabel>
            </SidebarGroup>
            <SidebarGroup />
        </SidebarContent>
        <SidebarFooter />
    </Sidebar>
</template>

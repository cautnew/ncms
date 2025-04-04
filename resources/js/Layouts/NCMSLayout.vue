<script setup lang="ts">
import { ref, onMounted, onUnmounted, Ref } from "vue";
import { FontAwesomeIcon as fa } from "@fortawesome//vue-fontawesome";
import {
    faUpRightAndDownLeftFromCenter,
    faDownLeftAndUpRightToCenter,
} from "@fortawesome//free-solid-svg-icons";
import {
    SidebarProvider,
    SidebarTrigger,
    useSidebar,
} from "@//components/ui/sidebar";
import { Breadcrumb, BreadcrumbList } from "@/components/ui/breadcrumb";
import AppSidebar from "./NCMSLayoutSidebar.vue";
import Cookies from "js-cookie";

const bartopPrinc: Ref<HTMLElement | null> = ref(null);
const mainPrinc: Ref<HTMLElement | null> = ref(null);

const updateMarginTop = () => {
    if (bartopPrinc.value) {
        const marginTop = bartopPrinc.value.offsetHeight + "px";
        mainPrinc.value.style.marginTop = marginTop;
    }
};

const resizeObserver: ResizeObserver = new ResizeObserver(updateMarginTop);

const defaultOpen: boolean | undefined =
    Cookies.get("sidebar_state") === "true";

onMounted(() => {
    updateMarginTop();
    if (bartopPrinc.value) {
        resizeObserver.observe(bartopPrinc.value);
    }
    window.addEventListener("resize", updateMarginTop);
});
</script>

<template>
    <SidebarProvider :defaultOpen="defaultOpen">
        <AppSidebar />
        <div class="w-full min-h-screen">
            <header
                id="bartop-princ"
                ref="bartopPrinc"
                class="bg-white border-b-2 border-gray-200 fixed w-full"
                v-if="$slots.header"
            >
                <div
                    class="flex justify-start justify-items-center max-w-7xl px-2 py-2 sm:px-4 lg:px-8"
                >
                    <SidebarTrigger class="me-2" />
                    <div class="flex items-center justify-center">
                        <slot name="header" />
                    </div>
                </div>
            </header>
            <main
                id="main-princ"
                ref="mainPrinc"
                class="px-2 py-2 sm:px-4 lg:px-8"
            >
                <div
                    id="top-breadcrumb"
                    class="mt-1 mb-2"
                    v-if="$slots.breadcrumb"
                >
                    <Breadcrumb>
                        <BreadcrumbList>
                            <slot name="breadcrumb" />
                        </BreadcrumbList>
                    </Breadcrumb>
                </div>
                <slot />
            </main>
            <footer class="border-t-2 border-gray-200">
                <div class="max-w-7xl px-2 py-3 sm:px-4 lg:px-8">
                    <div class="text-center text-sm text-gray-500">
                        <p>&copy; 2025 - Todos os direitos reservados</p>
                    </div>
                </div>
            </footer>
        </div>
    </SidebarProvider>
</template>

<script setup lang="ts">
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from "@/components/ui/tooltip";

const props = defineProps({
    text: {
        type: String,
        default: "Tip",
    },
});
</script>

<template>
    <TooltipProvider>
        <Tooltip>
            <TooltipTrigger as-child>
                <slot />
            </TooltipTrigger>
            <TooltipContent>
                {{ props.text }}
            </TooltipContent>
        </Tooltip>
    </TooltipProvider>
</template>

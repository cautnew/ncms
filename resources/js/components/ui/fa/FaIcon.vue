<script setup>
import { FontAwesomeIcon as fa } from "@fortawesome/vue-fontawesome";
import { library, icon } from "@fortawesome/fontawesome-svg-core";
import { fas } from "@fortawesome/free-solid-svg-icons";

library.add(fas);

const props = defineProps({
    style: {
        type: String,
        default: 'fas',
    },
    icon: {
        type: String,
        required: true,
    },
});

const prepareIconObject = (style, iconName) => {
    return icon({ prefix: style, iconName });
};
</script>

<template>
    <KeepAlive>
        <fa :icon="prepareIconObject(style, icon).abstract[0]" />
    </KeepAlive>
</template>

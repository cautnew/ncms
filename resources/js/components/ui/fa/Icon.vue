<script setup>
import { computed } from "vue";
import { FontAwesomeIcon as fa } from "@fortawesome//vue-fontawesome";
import { faFile, faHome, faUser, faSun, faDesktop, faTv, faMoon } from "@fortawesome//free-solid-svg-icons";

const props = defineProps({
    as: {
        type: String,
        default: "faFile",
    },
});

// Mapeamento de componentes disponíveis
const components = {
    faFile, faHome, faUser, faSun, faDesktop, faTv, faMoon,
};

// Computar o componente dinâmico com base na prop "as"
const dynamicComponent = computed(() => components[props.as] || null);
</script>

<template>
    <KeepAlive>
        <fa :icon="dynamicComponent" />
    </KeepAlive>
</template>

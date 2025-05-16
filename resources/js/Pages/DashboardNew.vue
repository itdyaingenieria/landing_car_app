<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';

// Props received from the controller with type checking and default values
const props = defineProps({
    participants: {
        type: Array,
        default: () => []
    },
    winner: {
        type: Object,
        default: null
    },
    stats: {
        type: Object,
        default: () => ({
            total: 0,
            today: 0,
            canDrawWinner: false
        })
    }
});

// State variables
const isDrawingWinner = ref(false);
const searchQuery = ref('');

// Filtered participants by search with additional validation
const filteredParticipants = computed(() => {
    if (!searchQuery.value || !props.participants) return props.participants || [];

    const query = searchQuery.value.toLowerCase();
    return props.participants.filter(p => {
        try {
            return (
                (p.first_name && p.first_name.toLowerCase().includes(query)) ||
                (p.last_name && p.last_name.toLowerCase().includes(query)) ||
                (p.email && p.email.toLowerCase().includes(query)) ||
                (p.document_number && p.document_number.includes(query))
            );
        } catch (error) {
            console.error('Error filtrando participante:', error);
            return false;
        }
    });
});

// Method to select winner
async function drawWinner() {
    if (!props.stats?.canDrawWinner) {
        alert('Se necesitan al menos 5 participantes para seleccionar un ganador.');
        return;
    }

    isDrawingWinner.value = true;

    try {
        await axios.post('/draw-winner');
        // Reload the page to display the new winner
        router.reload();
    } catch (error) {
        console.error('Error al seleccionar ganador:', error);
        alert('Ocurrió un error al seleccionar el ganador. Por favor, intente nuevamente.');
    } finally {
        isDrawingWinner.value = false;
    }
}

// Format date function
function formatDate(dateString) {
    if (!dateString) return '';
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
    } catch (e) {
        return dateString;
    }
}
</script>

<template>

    <Head title="Panel de Administración" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Panel de Administración - Concurso Volkswagen
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Statistics and Winner Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Card Statistics -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Estadísticas</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                                <p class="text-sm text-gray-600">Total Participantes</p>
                                <p class="text-3xl font-bold text-[#001E50]">{{ stats?.total || 0 }}</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4 border border-green-100">
                                <p class="text-sm text-gray-600">Registros Hoy</p>
                                <p class="text-3xl font-bold text-[#001E50]">{{ stats?.today || 0 }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <Link :href="route('landing')" class="text-[#001E50] hover:text-indigo-700">
                            Ver Landing Page →
                            </Link>
                        </div>
                    </div>

                    <!-- Card Winner -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Ganador .:.</h3>

                        <div v-if="winner" class="bg-green-50 p-4 rounded-lg border border-green-100 mb-4">
                            <div class="flex justify-between flex-wrap">
                                <div>
                                    <p class="font-semibold text-lg">{{ winner.first_name }} {{ winner.last_name }}</p>
                                    <p>{{ winner.email }}</p>
                                    <p>{{ winner.phone }}</p>
                                </div>
                                <div>
                                    <p>{{ winner.document_number }}</p>
                                    <p v-if="winner.city && winner.department">
                                        {{ winner.city.name }}, {{ winner.department.name }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div v-else class="bg-yellow-50 p-4 rounded-lg border border-yellow-100 mb-4">
                            <p class="text-yellow-700">Aún no se ha seleccionado un ganador.</p>
                        </div>

                        <button @click="drawWinner" :disabled="!stats?.canDrawWinner || isDrawingWinner"
                            class="px-4 py-2 bg-[#001E50] text-white rounded-md hover:bg-indigo-700 disabled:bg-gray-400 disabled:cursor-not-allowed">
                            <span v-if="isDrawingWinner">Seleccionando...</span>
                            <span v-else>Seleccionar Ganador</span>
                        </button>

                        <p v-if="!stats?.canDrawWinner" class="mt-2 text-sm text-red-600">
                            Se necesitan al menos 5 participantes para seleccionar un ganador.
                        </p>
                    </div>
                </div>

                <!-- Participants list section -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-semibold text-gray-800">Lista de Participantes</h3>

                            <div class="flex space-x-4">
                                <div class="relative">
                                    <input type="text" v-model="searchQuery" placeholder="Buscar participante..."
                                        class="w-64 px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" />
                                    <div v-if="searchQuery" @click="searchQuery = ''"
                                        class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 cursor-pointer">
                                        ✕
                                    </div>
                                </div>

                                <!-- <a :href="route('participants.export')"
                                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 flex items-center"
                                    target="_blank">
                                    <span>Exportar Excel</span>
                                </a> -->
                            </div>
                        </div>

                        <div v-if="filteredParticipants && filteredParticipants.length > 0" class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nombre
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Documento
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ubicación
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Contacto
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fecha
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="participant in filteredParticipants" :key="participant.id"
                                        :class="{ 'bg-green-50': winner && winner.id === participant.id }">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ participant.first_name }} {{ participant.last_name }}
                                                    </div>
                                                    <div v-if="winner && winner.id === participant.id"
                                                        class="text-xs text-green-600 font-medium">
                                                        ★ Ganador
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ participant.document_number }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div v-if="participant.city && participant.department"
                                                class="text-sm text-gray-900">
                                                {{ participant.city.name }}, {{ participant.department.name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ participant.email }}</div>
                                            <div class="text-sm text-gray-500">{{ participant.phone }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ formatDate(participant.created_at) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else class="py-8 text-center text-gray-500">
                            No hay participantes registrados aún
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
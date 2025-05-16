<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import axios from 'axios';

const departments = ref([]);
const cities = ref([]);
const winner = ref(null);
const submitting = ref(false);
const errors = ref({});
const success = ref(false);

const form = ref({
    first_name: '',
    last_name: '',
    document_number: '',
    department_id: '',
    city_id: '',
    phone: '',
    email: '',
    habeas_data: false
});

onMounted(async () => {
    try {
        // Load departments and winner on page load
        const deptResponse = await axios.get('/departments');
        departments.value = deptResponse.data;

        const winnerResponse = await axios.get('/winner');
        if (winnerResponse.data && winnerResponse.data.winner) {
            winner.value = winnerResponse.data.winner;
        }
    } catch (error) {
        console.error('Error loading initial data', error);
    }
});

async function loadCities() {
    if (!form.value.department_id) {
        cities.value = [];
        form.value.city_id = '';
        return;
    }

    try {
        const response = await axios.get(`/cities?department_id=${form.value.department_id}`);
        cities.value = response.data;
        form.value.city_id = '';
    } catch (error) {
        console.error('Error loading cities', error);
    }
}

async function submitForm() {
    submitting.value = true;
    errors.value = {};

    try {
        await axios.post('/participants', form.value);
        success.value = true;
        resetForm();
        alert('Registro exitoso. ¡Buena suerte en el concurso!');
    } catch (error) {
        if (error.response && error.response.data && error.response.data.errors) {
            errors.value = error.response.data.errors;
        } else {
            console.error('Error submitting form', error);
            alert('Ocurrió un error al enviar el formulario. Por favor, intente nuevamente.');
        }
    } finally {
        submitting.value = false;
    }
}

function resetForm() {
    form.value = {
        first_name: '',
        last_name: '',
        document_number: '',
        department_id: '',
        city_id: '',
        phone: '',
        email: '',
        habeas_data: false
    };
    cities.value = [];
}

function maskDocumentNumber(doc) {
    if (!doc) return '';
    const lastDigits = doc.slice(-4);
    return '****' + lastDigits;
}

function maskEmail(email) {
    if (!email) return '';
    const [username, domain] = email.split('@');
    return username.charAt(0) + '***@' + domain;
}
</script>

<template>

    <Head>
        <title>Concurso Volkswagen</title>
    </Head>
    <div class="bg-gray-50 min-h-screen">
        <!-- Header with VW Logo -->
        <header class="bg-[#001E50] text-white shadow-md">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <div class="flex items-center">
                    <!-- Volkswagen logo -->
                    <svg class="h-10 w-auto" viewBox="0 0 64 64" fill="currentColor">
                        <path
                            d="M32 0C14.327 0 0 14.327 0 32s14.327 32 32 32 32-14.327 32-32S49.673 0 32 0zm0 2c16.568 0 30 13.432 30 30S48.568 62 32 62 2 48.568 2 32 15.432 2 32 2zm0 8a22 22 0 100 44 22 22 0 000-44zm-11 10h22v1H21v-1zm0 3h22v1H21v-1zm0 3h22v1H21v-1zm0 3h22v1H21v-1zm0 3h22v1H21v-1zm11-20a19 19 0 110 38 19 19 0 010-38z"
                            fill="white" />
                    </svg>
                    <h1 class="ml-3 text-2xl font-bold">Promoción Especial - Prueba de Concepto</h1>
                </div>
                <div>
                    <Link v-if="$page.props.auth && $page.props.auth.user" :href="route('dashboard')"
                        class="text-white hover:text-gray-200">
                    Panel de Administración
                    </Link>
                    <Link v-else :href="route('login')" class="text-white hover:text-gray-200">
                    Iniciar Sesión
                    </Link>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <!-- Banner Section -->
            <section class="mb-16 bg-gradient-to-r from-[#001E50] to-indigo-700 rounded-lg shadow-xl overflow-hidden">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-1/2 p-8 md:p-12 flex items-center">
                        <div>
                            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">¡Regístrate y gana con
                                Volkswagen!</h2>
                            <p class="text-white/90 mb-8">Participa en nuestro concurso exclusivo para clientes de
                                Volkswagen en Bogotá y podrías ser uno de nuestros afortunados ganadores.</p>
                            <a href="#registro"
                                class="inline-block bg-white text-[#001E50] font-semibold px-6 py-3 rounded-md hover:bg-gray-100 transition">
                                Registrarme ahora .:.
                            </a>
                        </div>
                    </div>
                    <div class="md:w-1/2 bg-gray-100 flex items-center justify-center p-4">
                        <div class="max-w-xs rounded-lg overflow-hidden shadow-md">
                            <img src="/img/promo1.png" alt="Volkswagen Promoción"
                                class="w-full h-auto object-contain" />
                        </div>
                    </div>
                </div>
            </section>

            <!-- Winner Display (if exists) -->
            <section v-if="winner" class="mb-16 bg-white p-8 rounded-lg shadow-md border-l-4 border-[#001E50]">
                <h2 class="text-2xl font-bold text-[#001E50] mb-6">¡Felicitaciones a nuestro ganador! 🎉</h2>
                <div class="flex flex-col md:flex-row justify-between bg-gray-50 p-6 rounded-lg">
                    <div>
                        <p class="mb-2"><span class="font-semibold">Nombre:</span> {{ winner.first_name }} {{
                            winner.last_name }}</p>
                        <p><span class="font-semibold">Ubicación:</span> {{ winner.city.name }}, {{
                            winner.department.name }}</p>
                    </div>
                    <div>
                        <p class="mb-2"><span class="font-semibold">Documento:</span> {{
                            maskDocumentNumber(winner.document_number) }}</p>
                        <p><span class="font-semibold">Email:</span> {{ maskEmail(winner.email) }}</p>
                    </div>
                </div>
            </section>

            <!-- Registration Form -->
            <section id="registro" class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-[#001E50] text-white py-4 px-6">
                    <h2 class="text-xl font-semibold">Registro para participar</h2>
                </div>

                <form @submit.prevent="submitForm" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                            <input id="first_name" v-model="form.first_name" type="text"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                :class="{ 'border-red-500': errors.first_name }">
                            <p v-if="errors.first_name" class="mt-1 text-sm text-red-600">{{ errors.first_name[0] }}</p>
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Apellido</label>
                            <input id="last_name" v-model="form.last_name" type="text"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                :class="{ 'border-red-500': errors.last_name }">
                            <p v-if="errors.last_name" class="mt-1 text-sm text-red-600">{{ errors.last_name[0] }}</p>
                        </div>

                        <div>
                            <label for="document_number"
                                class="block text-sm font-medium text-gray-700 mb-1">Cédula</label>
                            <input id="document_number" v-model="form.document_number" type="text"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                :class="{ 'border-red-500': errors.document_number }">
                            <p v-if="errors.document_number" class="mt-1 text-sm text-red-600">{{
                                errors.document_number[0] }}</p>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                            <input id="phone" v-model="form.phone" type="text"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                :class="{ 'border-red-500': errors.phone }">
                            <p v-if="errors.phone" class="mt-1 text-sm text-red-600">{{ errors.phone[0] }}</p>
                        </div>

                        <div>
                            <label for="department_id"
                                class="block text-sm font-medium text-gray-700 mb-1">Departamento</label>
                            <select id="department_id" v-model="form.department_id" @change="loadCities"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                :class="{ 'border-red-500': errors.department_id }">
                                <option value="">Seleccione un departamento</option>
                                <option v-for="dept in departments" :key="dept.id" :value="dept.id">{{ dept.name }}
                                </option>
                            </select>
                            <p v-if="errors.department_id" class="mt-1 text-sm text-red-600">{{ errors.department_id[0]
                                }}</p>
                        </div>

                        <div>
                            <label for="city_id" class="block text-sm font-medium text-gray-700 mb-1">Ciudad</label>
                            <select id="city_id" v-model="form.city_id"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                :class="{ 'border-red-500': errors.city_id }" :disabled="!form.department_id">
                                <option value="">Seleccione una ciudad</option>
                                <option v-for="city in cities" :key="city.id" :value="city.id">{{ city.name }}</option>
                            </select>
                            <p v-if="errors.city_id" class="mt-1 text-sm text-red-600">{{ errors.city_id[0] }}</p>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input id="email" v-model="form.email" type="email"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                :class="{ 'border-red-500': errors.email }">
                            <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email[0] }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="flex items-center">
                            <input v-model="form.habeas_data" type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                :class="{ 'border-red-500': errors.habeas_data }">
                            <span class="ml-2 text-sm text-gray-600">
                                Autorizo el tratamiento de mis datos de acuerdo con la
                                <a href="#" class="text-[#001E50] hover:underline">política de protección de datos
                                    personales</a>.
                            </span>
                        </label>
                        <p v-if="errors.habeas_data" class="mt-1 text-sm text-red-600">{{ errors.habeas_data[0] }}</p>
                    </div>

                    <div class="mt-8">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-[#001E50] hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            :disabled="submitting">
                            <span v-if="submitting">Enviando...</span>
                            <span v-else>Participar ahora</span>
                        </button>
                    </div>
                </form>
            </section>
        </main>

        <footer class="bg-gray-100 border-t border-gray-200 mt-16">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <p class="text-sm text-gray-600">&copy; {{ new Date().getFullYear() }} Volkswagen Colombia.
                            Todos los derechos reservados.</p>
                    </div>
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-600 hover:text-[#001E50]">Términos y condiciones</a>
                        <a href="#" class="text-gray-600 hover:text-[#001E50]">Política de privacidad</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
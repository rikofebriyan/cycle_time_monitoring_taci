<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('background')?.classList.add('hidden');
}
</script>

<template>

    <Head title="Dashboard" />

    <div class="relative min-h-screen text-black/50 dark:text-white/50">
        <!-- Background Image -->
        <img id="background" src="/images/background.jpg" class="fixed top-0 left-0 w-full h-full object-cover z-0"
            @error="handleImageError" />

        <!-- Main Content -->
        <div class="relative z-10 flex flex-col min-h-screen px-6 bg-black/50 backdrop-blur-sm">
            <!-- Header -->
            <header class="w-full max-w-7xl mx-auto flex justify-between items-center py-6 text-white">
                <h1 class="text-2xl font-bold">Monitoring App</h1>
                <nav v-if="canLogin" class="flex space-x-4">
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                        class="px-4 py-2 rounded-md bg-white/10 hover:bg-white/20 transition">
                    Dashboard
                    </Link>
                    <template v-else>
                        <Link :href="route('login')"
                            class="px-4 py-2 rounded-md bg-white/10 hover:bg-white/20 transition">
                        Log in
                        </Link>
                        <Link v-if="canRegister" :href="route('register')"
                            class="px-4 py-2 rounded-md bg-white/10 hover:bg-white/20 transition">
                        Register
                        </Link>
                    </template>
                </nav>
            </header>

            <!-- Main Section -->
            <main class="flex-grow flex flex-col items-center justify-center text-white text-center">
                <h2 class="text-6xl font-bold drop-shadow-xl mb-12">Toyoda Precepts / Basic Philosophy</h2>

                 
        <div class="bg-white/10 backdrop-blur-md p-12 rounded-2xl max-w-5xl shadow-lg text-left">
            <div class="flex items-center space-x-4">
        <img src="/images/toyoda.jpg" alt="Logo" class="w-80 h-80" />
                    <ul class="list-decimal list-inside text-2xl space-y-2">
                        <li>Always be faithful to your duties, thereby contributing to the Company and to the overall good.</li>
                        <li>Always be studious and creative, striving to stay ahead of the times.</li>
                        <li>Always be practical and avoid frivolousness.</li>
                        <li>Always strive to build a homelike atmosphere at work that is warm and friendly.</li>
                        <li>Always have respect for God, and remember to be grateful at all times.</li>
                    </ul>
                </div>
    </div>
                
            </main>

            <footer class="py-6 text-center text-white/70 text-sm">
                Powered by UAI
            </footer>
        </div>
    </div>
</template>
 
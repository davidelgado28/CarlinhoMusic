<template>
  <div class="app-container">
    <aside class="sidebar">
      <nav>
        <router-link to="/">Início</router-link>
        <router-link to="/playlists">Playlists</router-link>
      </nav>
    </aside>

    <main class="main-content">
      <router-view />
    </main>

    <footer class="global-player">
      <div v-if="playerStore.currentTrack">
        <p>Tocando: {{ playerStore.currentTrack.title }} - {{ playerStore.currentTrack.artist_name }}</p>
        <button @click="playerStore.togglePlay">
          {{ playerStore.isPlaying ? 'Pausar' : 'Tocar' }}
        </button>
        <span>{{ formatTime(playerStore.currentTime) }} / {{ formatTime(playerStore.duration) }}</span>
      </div>
      <div v-else>
        Nenhuma música selecionada.
      </div>
    </footer>
  </div>
</template>

<script setup>
import { usePlayerStore } from '@/stores/player';

const playerStore = usePlayerStore();

const formatTime = (seconds) => {
  const m = Math.floor(seconds / 60).toString().padStart(2, '0');
  const s = (seconds % 60).toString().padStart(2, '0');
  return `${m}:${s}`;
};
</script>

<style scoped>
.app-container {
  display: flex;
  height: 100vh;
  flex-direction: column;
}
.global-player {
  position: fixed;
  bottom: 0;
  width: 100%;
  height: 80px;
  background-color: #1a1a1a;
  color: white;
  display: flex;
  align-items: center;
  padding: 0 20px;
}
</style>

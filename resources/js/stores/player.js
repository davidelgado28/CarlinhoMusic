import { defineStore } from 'pinia';
import axios from 'axios';

export const usePlayerStore = defineStore('player', {
  state: () => ({
    audio: new Audio(),
    currentTrack: null,
    isPlaying: false,
    currentTime: 0,
    duration: 0,
    queue: [],
    currentIndex: -1,
  }),

  actions: {
    initPlayer() {
      if (this.audio.listenersAttached) return;

      this.audio.addEventListener('timeupdate', () => {
        this.currentTime = Math.floor(this.audio.currentTime);
        if (this.currentTime > 0 && this.currentTime % 10 === 0) {
            this.syncProgressToBackend();
        }
      });

      this.audio.addEventListener('ended', () => this.next());
      this.audio.listenersAttached = true;
    },

    async fetchAndPlay(externalId) {
      try {
        const response = await axios.get(`/api/v1/tracks/stream/${externalId}`);
        const trackData = response.data.data;
        await this.playTrack(trackData);
      } catch (error) {
        console.error("Erro ao carregar stream:", error);
      }
    },

    async playTrack(track) {
      this.initPlayer();
      this.currentTrack = track;
      this.audio.src = track.stream_url;
      
      try {
        await this.audio.play();
        this.isPlaying = true;
      } catch (e) {
        this.isPlaying = false;
      }
    },

    togglePlay() {
      if (!this.currentTrack) return;
      this.isPlaying ? this.audio.pause() : this.audio.play();
      this.isPlaying = !this.isPlaying;
    },

    next() {
    },

    syncProgressToBackend() {
        if (!this.currentTrack) return;
        axios.post(`/api/v1/tracks/${this.currentTrack.id}/progress`, {
            position_seconds: this.currentTime
        }).catch(() => {  });
    }
  }
});

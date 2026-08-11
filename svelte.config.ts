import adapter from '@sveltejs/adapter-node';
import type { Config } from '@sveltejs/kit';
import { vitePreprocess } from '@sveltejs/vite-plugin-svelte';

const config: Config = {
	preprocess: vitePreprocess(),
	kit: {
		// adapter-node runs the built server under Bun (`bun ./build/index.js`),
		// which is required so `bun:sqlite` is available at runtime.
		adapter: adapter(),
	},
};

export default config;

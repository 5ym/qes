<script lang="ts">
import '../app.css';
import { enhance } from '$app/forms';
import { page } from '$app/state';

let { children } = $props();
const user = $derived(page.data.user);
</script>

<div class="bg-base-200 flex min-h-dvh flex-col">
	<header class="navbar bg-base-100 border-base-300 border-b shadow-sm">
		<div class="mx-auto flex w-full max-w-4xl items-center px-4">
			<a href="/" class="btn btn-ghost text-xl font-bold">
				<span class="text-primary">QR</span>
				Entry System
			</a>
			<div class="flex-1"></div>
			{#if user}
				<a href="/list" class="btn btn-ghost btn-sm">一覧</a>
				<span class="hidden text-sm opacity-70 sm:inline">{user.email}</span>
				<form method="POST" action="/logout" use:enhance class="inline">
					<button type="submit" class="btn btn-outline btn-sm">ログアウト</button>
				</form>
			{:else}
				<a href="/login" class="btn btn-primary btn-sm">スタッフログイン</a>
			{/if}
		</div>
	</header>

	<main class="mx-auto w-full max-w-4xl flex-1 px-4 py-8">
		{@render children()}
	</main>

	<footer class="footer footer-center text-base-content/60 p-4 text-sm">
		<aside><p>QR Entry System · SvelteKit + Bun + SQLite + DaisyUI</p></aside>
	</footer>
</div>

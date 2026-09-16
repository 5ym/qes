<script lang="ts">
import '../app.scss';
import { enhance } from '$app/forms';
import { page } from '$app/state';

let { children } = $props();
const user = $derived(page.data.user);
</script>

<div class="shell">
	<header class="bar">
		<div class="page cluster">
			<a href="/" class="button ghost brand">
				<span class="qr">QR</span>
				Entry System
			</a>
			<div class="grow"></div>
			{#if user}
				<a href="/list" class="button ghost mini">一覧</a>
				<span class="mail small muted">{user.email}</span>
				<form method="POST" action="/logout" use:enhance>
					<button type="submit" class="outline mini">ログアウト</button>
				</form>
			{:else}
				<a href="/login" class="button mini">スタッフログイン</a>
			{/if}
		</div>
	</header>

	<main class="page main">
		{@render children()}
	</main>

	<footer class="foot small muted">
		<p>QR Entry System · SvelteKit + Bun + SQLite + Pico CSS</p>
	</footer>
</div>

<style>
/* 画面いっぱいに縦積みして、main だけ伸ばす。footer を下端に貼り付けるため */
.shell {
	display: flex;
	flex-direction: column;
	min-height: 100dvh;
	background: var(--pico-background-color);
}
.bar {
	border-bottom: 1px solid var(--ui-base-300);
	background: var(--ui-surface);
	box-shadow: var(--ui-shadow);
	padding-block: 0.5rem;
}
.brand {
	font-size: 1.25rem;
}
.qr {
	color: var(--pico-primary);
}
/* メールアドレスは狭い画面では出さない(ボタンを押しやすさ優先) */
.mail {
	display: none;
}
@media (min-width: 640px) {
	.mail {
		display: inline;
	}
}
.main {
	flex: 1 1 auto;
	padding-block: 2rem;
}
.foot {
	padding: 1rem;
	text-align: center;
}
</style>

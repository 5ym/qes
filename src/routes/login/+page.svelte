<script lang="ts">
import { enhance } from '$app/forms';
import type { ActionData } from './$types';

let { form }: { form: ActionData } = $props();
let loading = $state(false);
</script>

<svelte:head>
	<title>スタッフログイン · QR Entry System</title>
</svelte:head>

<div class="center">
	<div class="panel body box">
		<h1>スタッフログイン</h1>
		{#if form?.error}
			<div class="note err">{form.error}</div>
		{/if}
		<form
			method="POST"
			use:enhance={() => {
				loading = true;
				return async ({ update }) => {
					await update();
					loading = false;
				};
			}}
			class="stack"
		>
			<label class="field">
				<span class="lab">メールアドレス</span>
				<input name="email" type="email" required value={form?.email ?? ''}>
			</label>
			<label class="field">
				<span class="lab">パスワード</span>
				<input name="password" type="password" required>
			</label>
			<button type="submit" disabled={loading}>
				{#if loading}
					<span class="spin"></span>
				{/if}
				ログイン
			</button>
		</form>
	</div>
</div>

<style>
.center {
	display: flex;
	justify-content: center;
}
.box {
	width: 100%;
	max-width: 28rem;
}
</style>

<script lang="ts">
import { enhance } from '$app/forms';
import type { ActionData } from './$types';

let { form }: { form: ActionData } = $props();
let loading = $state(false);
</script>

<svelte:head>
	<title>スタッフログイン · QR Entry System</title>
</svelte:head>

<div class="flex justify-center">
	<div class="card bg-base-100 w-full max-w-md shadow-lg">
		<div class="card-body">
			<h1 class="card-title text-2xl">スタッフログイン</h1>
			{#if form?.error}
				<div class="alert alert-error text-sm">{form.error}</div>
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
				class="flex flex-col gap-4"
			>
				<label class="form-control w-full">
					<span class="label-text mb-1">メールアドレス</span>
					<input
						name="email"
						type="email"
						required
						value={form?.email ?? ''}
						class="input input-bordered w-full"
					>
				</label>
				<label class="form-control w-full">
					<span class="label-text mb-1">パスワード</span>
					<input name="password" type="password" required class="input input-bordered w-full">
				</label>
				<button type="submit" class="btn btn-primary mt-2" disabled={loading}>
					{#if loading}
						<span class="loading loading-spinner"></span>
					{/if}
					ログイン
				</button>
			</form>
		</div>
	</div>
</div>
